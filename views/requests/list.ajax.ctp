<?php
/* @var $form FormHelper */
/* @var $html HtmlHelper */
/* @var $js JsHelper */
/* @var $limit int */
/* @var $data array */
/* @var $rdurl String */
/* @var $stt int */
$stt = ($this->Paginator->current() - 1 ) * $limit;
$rdurl = $html->url(array('action' => 'index', $rdurl));

/**
 * Make up Status of Request
 * @param int $statusid
 * @return String
 */
function makeupStatus($statusid) {
    switch ($statusid) {
        case REQUEST_STATUS_APROVED:
            return __('予約した', true);
            break;

        case REQUEST_STATUS_CANCELED:
            return __('キャンセル', true);
            break;
        case REQUEST_STATUS_FINISH:
            return __('使用した', true);
            break;
        default:
            return __('知らない', true);
            break;
    }
}
?>
<form name="form1" action="requests/action" method="post">
    <div class="module_header">
        <div class="header_action">
            <?php
            $options = array(
                //1 => '終了',
                2 => 'キャンセル'
            );
            echo $form->select('itemaction', $options, null, array('empty' => '--Select--'));
            echo $form->button('Submit', array('type' => 'submit'));
            ?>
            <ul class="tabs">
                <li class="mod_hea_bt"><?php echo $html->link(__('予約追加', true), array('action' => 'admin_add'), array('title' => __('予約', true), 'onclick' => 'doAddRequest();return false;')); ?></li>
            </ul>
            <ul class="tabs" style="margin-right: 5px">
                <li class="mod_hea_bt"><?php echo $html->link(__('CSV輸出', true), array('action' => 'admin_csvexport'), array('title' => __('CSV輸出', true), 'onclick' => 'gotoCSVExport();return false;')); ?></li>
            </ul>
        </div>
    </div>
    <table class="tablesorter" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th style="width: 5%" class="tableheader"><?php __("#"); ?></th>
                <th style="width: 5%" class="tableheader"><?php echo $form->checkbox('allbox', array('title' => __('Select all', true), 'class' => 'cb_allItem', 'onclick' => 'checkAll()')); ?></th>
                <th style="width: 10%" class="tableheader"><?php echo $this->Paginator->sort(__('室', true), 'Room.name'); ?></th>
                <th style="width: 15%" class="tableheader"><?php echo $this->Paginator->sort(__('テナント', true), 'Reqester.fullname'); ?></th>
                <th style="width: 15%" class="tableheader"><?php echo $this->Paginator->sort(__('始まり', true), 'begin_time'); ?></th>
                <th style="width: 15%" class="tableheader"><?php echo $this->Paginator->sort(__('終わり', true), 'end_time'); ?></th>
                <th style="width: 15%" class="tableheader"><?php echo $this->Paginator->sort(__('更新時', true), 'update_time'); ?></th>
                <th style="width: 10%" class="tableheader"><?php echo $this->Paginator->sort(__('状態', true), 'status'); ?></th>
                <th style="width: 10%" class="tableheader"><?php __('アクション'); ?></th>
            </tr>
        </thead>
        <?php if (count($list) == 0): ?>
            <tr>
                <td colspan="11" align="center" style="height: 100px">
                    <strong><?php __('Not found any records'); ?></strong>
                </td>
            </tr>
        <?php else: ?>
            <?php foreach ($list as $item) : ?>
                <?php
                $class = null;
                if ($stt++ % 2 == 0) {
                    $class = ' class="altrow"';
                }
                ?>
                <tr<?php echo $class; ?>>
                    <td align="center"><?php echo $stt; ?>&nbsp;</td>
                    <td align="center"><?php echo $form->checkbox('Request.SelectItem.' . ($stt - 1), array('value' => $item['Request']['id'], 'title' => __('Select # ' . $stt, true), 'class' => 'cb_item')); ?></td>
                    <td align="left"><?php echo $item['Room']['name']; ?>&nbsp;</td>
                    <td align="left"><?php echo $item['Requester']['fullname']; ?>&nbsp;</td>
                    <td align="center"><?php echo $item['Request']['begin_time']; ?>&nbsp;</td>
                    <td align="left"><?php echo $item['Request']['end_time']; ?>&nbsp;</td>
                    <td align="center"><?php echo $item['Request']['update_time']; ?>&nbsp;</td>
                    <td align="center"><?php echo makeupStatus($item['Request']['status']); ?>&nbsp;</td>
                    <td style="padding: 5px 5px" align="center">
                        <?php
                        echo $html->image('admin_layout/icn_aprove.gif', array('url' => array('action' => 'view', $item['Request']['id']), 'title' => __('View # ' . $stt, true), 'alt' => 'view'));
                        //echo $html->image('admin_layout/icn_edit.png', array('url' => array('action' => 'admin_edit', $item['Request']['id']), 'title' => __('Edit # ' . $stt, true), 'alt' => 'edit'));
                        echo $html->image('admin_layout/icn_trash.png', array('url' => array('action' => 'delete', $item['Request']['id']), 'title' => __('Delete # ' . $stt, true), 'alt' => 'delete'));
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</form>
<div class="module_footer">
    <div id="limit">
        <?php
        $rdOption = array('5' => '5', '10' => '10', '15' => '15', '20' => '20', '25' => '25');
        echo sprintf(__('Show %s records on a page.', true), $form->select('rd', $rdOption, $limit, array('empty' => false)));
        ?>
    </div>
    <div id="pagination">
        <?php
        echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class' => 'disabled'));
        echo ' | ';
        echo $this->Paginator->numbers();
        echo ' | ';
        echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));
        ?>
    </div>
    <div id="count">
        <?php
        echo $this->Paginator->counter(array(
            'format' => __('Result %current% records out of %count% total.', true)
        ));
        ?>
    </div>
</div>
<?php
$js->get("#rd")->event('change', "$('#result_box').load('" . $rdurl . "'+this.value);");
$js->get("a[href*=/sort:], a[href*=/page:]")->event('click', "$('#result_box').load($(this).attr('href'));");
echo $js->writeBuffer();
?>
<script type="text/javascript">
    function checkAll(){
        for (var i=0;i<document.form1.elements.length;i++)
        {
            var e=document.form1.elements[i];
            if ((e.name != 'allbox') && (e.type=='checkbox'))
            {
                e.checked=document.form1.allbox.checked;
            }
        }
    }
</script>