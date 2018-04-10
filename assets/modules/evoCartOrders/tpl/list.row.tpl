<tr>
    <td>[+id+]</td>
    <td class="nowrap">[+date+]<br>[+time+]</td>
    <td><small>[+delivery+]<br>[+address+]</small></td>
    <td>[+phone+]<br>[+name+] <small><a href="mailto:[+email+]">[+email+]</a></small></td>
    <td>[+payment.icon+]</td>
    <td><strong>[+price+]</strong></td>
    <td>
        <a class="btn btn-primary js-modal-toggler" data-id="[+id+]" tooltip="Состав заказа"><i class="fa fa-list" aria-hidden="true"></i></a>
        <div class="js-data-for-modal">[+orderdata+]<hr><em>[+message+]</em></div>
    </td>
    <td>
        <select name="status" data-id="[+id+]" class="js-status">
            [+statuses+]
        </select>
    </td>
</tr>
