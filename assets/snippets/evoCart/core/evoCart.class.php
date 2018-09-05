<?php
class evoCart {
        private $cart = array();
        private $modx = null;
        private $config = [
            'chunk' => 'evoCartRow',
            'tvs' => 'img,price,brand,article',
            'availability' => null,
            'nds' => 0
        ];
        public $success = false;
        public $qty = 0;
        public $total = 0;
        public $dsq = 0;
        public $shipping = 0;

    /**
     * @param DocumentParser $modx
     * @param array $cfg
     * @throws Exception
     */
    public function __construct($modx, $cfg = array()) {
        if ($modx instanceof DocumentParser) {
            $this->modx = $modx;
            $this->config = array_merge($this->config, $cfg);
        } else {
            throw new Exception('MODX var is not instaceof DocumentParser');
        }
        $this->cart = &$_SESSION['cart'];
        if (empty($this->cart)) {
            $this->cart = array();
        }
    }

    /**
     * @param $id
     * @param int $count
     * @param array $options
     * @return object
     */
    public function add($id, $count = 1, $options = array()) {
        if (!$id || !is_numeric($id)) return false;
        $this->success = true;
        $key = md5($id.serialize($options));
        if (!isset($this->cart[$key])) {
            $this->cart[$key] = array(
                'id' => $id,
                'count' => $count,
                'options' => $options
            );
        } else {
            if (!$this->update($key, $this->cart[$key]['count'] + $count)) {
                $this->success = false;
            }
        }
        return $this;
    }

    /**
     * @param $key
     * @return bool
     */
    public function remove($key) {
        if (!is_scalar($key)) {
            $this->success = false;
        } else {
            unset($this->cart[$key]);
            $this->success = true;
        }
        return $this;
    }

    /**
     * @return bool
     */
    public function clear() {
        $this->cart = array();
        $this->success = true;
        return $this;
    }

    /**
     * @param $key
     * @param int $count
     * @return bool
     */
    public function update($key, $count = 0) {
        if (!is_scalar($key) || !isset($this->cart[$key])) return false;
        if ($count <= 0) {
            $this->success = $this->remove($key);
        } else {
            if($this->config['availability']) {
                $docid = $this->cart[$key]['id'];
                $available = $this->modx->db->getValue($this->modx->db->query('SELECT tc.`value` FROM '.$this->modx->getFullTableName('site_tmplvar_contentvalues').' AS `tc` LEFT JOIN '.$this->modx->getFullTableName('site_tmplvars').' AS `t` ON `t`.`id` = `tc`.`tmplvarid` WHERE `t`.`name` = "'.trim($this->config['availability']).'" AND `tc`.`contentid` = '.$docid.' LIMIT 1'));
                if((int)$available < (int)$count) {
                    $this->success = false;
                    $this->error = 'На складе только '.$available.' шт.';
                    return $this;
                }
            }
            $this->cart[$key]['count'] = $count;
            $this->success = true;
        }
        return $this;
    }

    /**
     * @return array
     */
    public function status() {
        $qty = 0;
        foreach ($this->cart as $item) {
            $qty = $qty + $item['count'];
        }
        $out = [
            'qty' => $qty,
            'cart' => $this->cart
        ];
        return $out;
    }

    public function total() {
        $qty = 0;
        foreach ($this->cart as $item) {
            $qty = $qty + $item['count'];
        }
        return $qty;
    }

    public function shipping() {
        $params = [
            'delivery' => $this->modx->stripTags($_REQUEST['delivery']),
            'city' => !empty($_REQUEST['city']) ? $this->modx->stripTags($_REQUEST['city']) : $this->modx->stripTags($_REQUEST['address']),
            'total' => $this->total,
            'dsq' => $this->dsq,
            'qty' => $this->qty
        ];
        $out = $this->modx->invokeEvent('OnBeforeEvoCartRender', $params);
        $this->shipping = is_array($out) ? $out[0] : 0;
        return;
    }

    public function getFullData($render = false)
    {
        if(count($this->cart) < 1) {
            http_response_code(400);
            die('Корзина пуста');
        }
        include_once(MODX_BASE_PATH.'assets/snippets/DocLister/lib/DLTemplate.class.php');
        $dlt = DLTemplate::getInstance($this->modx);
        foreach($this->cart as $item) {
            $prodIds[] = $item['id'];
        }
        //получаем все данные по товарам и обрабатываем в prepare
        $neededFields = [
            'id', 'pagetitle', 'alias', 'url', 'e_title', 'price', 'dsq', 'img'
        ];
        $tvsArr = explode(',', $this->config['tvs']);
        foreach($tvsArr as $tv) {
            $neededFields[] = 'tv.'.$tv;
        }
        $itemsDataJson = $this->modx->runSnippet('DocLister', [
            'documents' => implode(',', $prodIds),
            'api' => implode(',', $neededFields),
            'tvList' => $this->config['tvs'],
            'prepare' => 'prepareEvoCart'
        ]);
        $itemsData = json_decode($itemsDataJson, true);
        //выводы
        $cartRow = '';
        $out = [];
        foreach($this->cart as $key => $item) {
            $ph = $itemsData[$item['id']];
            unset($ph['content'], $ph['content_with_tv'], $ph['content_with_tv_index']);
            $ph['key'] = $key;
            $ph['tv_price'] = (float)str_replace(',', '.', $ph['tv_price']);
            $ph['count'] = (int)$item['count'];
            $this->qty += $ph['count'];
            if(count($item['options']) > 0) {
                foreach($item['options'] as $k => $v) {
                    $ph['data.'.$k] = $v;
                }
            }
            if($ph['dsq'] > 0) {
                $ph['dsq.price'] = $ph['tv_price'] * (100 - $ph['dsq'])/ 100;
                $ph['dsq.size'] = $ph['tv_price'] / 100 * $ph['dsq'];
                $ph['dsq.total'] = $ph['dsq.size'] * $ph['count'];
                $this->dsq += $ph['dsq.total'];
            } else {
                $ph['dsq.price'] = $ph['dsq.size'] = $ph['dsq.total'] = 0;
            }
            $ph['total'] = $ph['tv_price'] * $ph['count'];
            $this->total += $ph['total'];
            $number_format = ['tv_price', 'dsq.price', 'dsq.size', 'dsq.total', 'total'];
            foreach($number_format as $plh) {
                $ph['f.'.$plh] = number_format($ph[$plh], 2, '.', ' ');
            }
            if($render) {
                $cartRow .= $dlt->parseChunk($this->config['chunk'],$ph);
            } else {
                $cartRow[] = $ph;
            }
        }
        //$shipping
        $this->shipping();
        $result = [
            'cart' => $cartRow,
            'total' => number_format($this->total, 2, '.', ' '),
            'dsq' => number_format($this->dsq, 2, '.', ' '),
            'shipping' => number_format($this->shipping, 2, '.', ' ')
        ];
        $itogo = number_format($this->total - $this->dsq + $this->shipping, 2, '.', ' ');
        if((int)$this->config['nds'] > 0) {
            $result['itogo'] = $itogo * (100 + (int)$this->config['nds'])/100;
        } else {
            $result['itogo'] = $itogo;
        }

        if($render) {
            echo json_encode($result);
            die();
        }
        return $result;
    }

    public function fullDataResult() {

    }

    /**
     * @param $num
     * @param array $forms
     * @return mixed
     */
    public function formatPlural($num,$forms = array()) {
        $n = abs($num) % 100;
        $n1 = $n % 10;
        if (!$n || ($n > 10 && $n < 20)) return $forms[2];
        if ($n1 > 1 && $n1 < 5) return $forms[1];
        if ($n1 == 1) return $forms[0];
        return $forms[2];
    }

    public function jsonResult($error = null) {
        if($this->success) {
            $result = [
                'data' => $this->status(),
                'error' => false
            ];
            echo json_encode($result);
            die();
        } else {
            http_response_code(400);
            if(!$error && $this->error) {
                $error = $this->error;
            }
            die($error ?: 'Ошибка');
        }

    }
}
