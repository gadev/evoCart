<div class="tab-pane" id="evoCartPane">
    <script type="text/javascript">
        tpResources = new WebFXTabPane(document.getElementById('evoCartPane'));
    </script>

    <div class="tab-page" id="tabList">
        <h2 class="tab"><i class="fa fa-list"></i> Список</h2>
        <script type="text/javascript">tpResources.addTabPage(document.getElementById('tabList'));</script>
        <div class="tab-body">
            <div class="box">
                <form action="[+moduleurl+]" method="POST" class="js-ec-form">
                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label for="f0">Сортировка</label>
                            <select name="orderby" class="form-control js-ec-sort" id="f0">
                                <option value="id:desc">Сначала новые</option>
                                <option value="id:asc">Сначала старые</option>
                                <option value="price:asc">Сначала дешевые</option>
                                <option value="price:desc">Сначала дорогие</option>
                            </select>
                        </div>
                        <div class="form-group col-md-1">
                            <label for="f00">Показывать</label>
                            <select name="display" class="form-control js-ec-display" id="f00">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                        <div class="form-group col-md-1">
                            <label for="f1">#</label>
                            <input type="number" name="id" class="form-control" id="f1">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="f2">Телефон</label>
                            <input type="text" name="phone" class="form-control" id="f2">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="f3">Email</label>
                            <input type="text" name="email" class="form-control" id="f3">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="f4">Статус</label>
                            <select name="status" class="form-control" id="f4">
                                <option value=""></option>
                                <option value="1">Новый</option>
                                <option value="10">Обработан</option>
                                <option value="15">Оплачен</option>
                                <option value="20">Выполнен</option>
                                <option value="30">Отменен</option>
                            </select>
                        </div>
                        <div class="form-group col-md-1">
                            <label for="f5"> &nbsp;</label>
                            <button class="btn btn-primary form-control"><i class="fa fa-search"></i></button>
                        </div>
                        <div class="form-group col-md-1">
                            <label for="f6"> &nbsp;</label>
                            <a class="btn btn-secondary form-control" href="[+moduleurl+]">Сброс</a>
                        </div>
                        <div class="form-group hide">
                            <input type="text" name="page" class="form-control" id="js-ec-page">
                        </div>
                    </div>
                </form>
            </div>

            <table class="table data table-striped table-hover">
                <col width="20" valign="top">

                <thead>
                    <tr>
                        <th width="20">#</th>
                        <th>Дата</th>
                        <th>Доставка</th>
                        <th>Заказчик</th>
                        <th></th>
                        <th>Сумма</th>
                        <th></th>
                        <th>Статус</th>
                    </tr>
                </thead>
                <tbody class="js-ec-list">
                    [+list+]
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="8" class="js-ec-pages">
                            [+pages+]
                        </td>
                    </tr>
                </tfoot>
    		</table>
        </div>
    </div>

</div>
