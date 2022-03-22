{include common/header@ebcms/ucenter-web}
<style>
    a {
        text-decoration: none;
    }
</style>
<script>
    function deleteMessage(id) {
        if (confirm('确定删除吗？删除后不可恢复！')) {
            $.ajax({
                type: "DELETE",
                url: "{echo $router->build('/ebcms/ucenter-web/message')}?id=" + id,
                dataType: "JSON",
                success: function(response) {
                    if (response.code) {
                        alert(response.message);
                    } else {
                        location.reload();
                    }
                }
            });
        }
    }

    function readMessage(id, is_read) {
        $.ajax({
            type: "POST",
            url: "{echo $router->build('/ebcms/ucenter-web/message')}",
            data: {
                id: id,
                is_read: is_read
            },
            dataType: "JSON",
            success: function(response) {
                if (response.code) {
                    alert(response.message);
                }
            }
        });
    }
</script>
<div class="container">
    <div class="h1 my-4">系统消息</div>
    <div class="mb-3">
        <form id="form_2" class="row gy-2 gx-3 align-items-center" action="{echo $router->build('/ebcms/ucenter-web/message')}" method="GET">

            <div class="col-auto">
                <label class="visually-hidden">状态</label>
                <select class="form-select" name="is_read" onchange="document.getElementById('form_2').submit();">
                    <option {if $request->get('is_read')=='' }selected{/if} value="">不限</option>
                    <option {if $request->get('is_read')=='0' }selected{/if} value="0">未读</option>
                    <option {if $request->get('is_read')=='1' }selected{/if} value="1">已读</option>
                </select>
            </div>

            <div class="col-auto">
                <label class="visually-hidden">搜索</label>
                <input type="search" class="form-control" name="q" value="{:$request->get('q')}" placeholder="搜索.." onchange="document.getElementById('form_2').submit();">
            </div>
            <input type="hidden" name="page" value="1">
        </form>
    </div>
    <?php
    function format_date($time)
    {
        $t = time() - $time;
        $f = array(
            '31536000' => '年',
            '2592000' => '个月',
            '604800' => '星期',
            '86400' => '天',
            '3600' => '小时',
            '60' => '分钟',
            '1' => '秒'
        );
        foreach ($f as $k => $v) {
            $c = floor($t / intval($k));
            if (0 != $c) {
                return $c . $v . '前';
            }
        }
    }
    ?>
    <div class="table-responsive mb-3">
        <table class="table table-borderless" id="tablexx">
            <thead>
                <tr>
                    <th class="text-nowrap" style="width:10px;"></th>
                    <th class="text-nowrap"></th>
                </tr>
            </thead>
            <tbody>
                {foreach $messages as $vo}
                <tr>
                    <td>
                        <div class="form-check">
                            <input type="checkbox" name="ids[]" value="{$vo.id}" class="form-check-input" id="checkbox_{$vo.id}">
                        </div>
                    </td>
                    <td>
                        {if $vo['is_read']}
                        <span class="" data-bs-toggle="collapse" data-bs-target="#collapse{$vo.id}" aria-expanded="false" aria-controls="collapse{$vo.id}">{$vo.title}</span>
                        {else}
                        <span class="fw-bold" onclick="readMessage('{$vo.id}');$(this).removeClass('fw-bold')" data-bs-toggle="collapse" data-bs-target="#collapse{$vo.id}" aria-expanded="false" aria-controls="collapse{$vo.id}">{$vo.title}</span>
                        {/if}
                        <span class="text-muted ms-3" title="{:date(DATE_ISO8601, $vo['send_time'])}">{:format_date($vo['send_time'])}</span>
                        <div class="collapse" id="collapse{$vo.id}">
                            <div class="border border-warning p-2 mt-2 bg-warning bg-opacity-10">
                                {echo $vo['body']}
                            </div>
                        </div>
                    </td>
                </tr>
                {/foreach}
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6">
                        <form class="row gy-2 gx-3 align-items-center">
                            <div class="col-auto">
                                <button type="button" class="btn btn-sm btn-secondary" id="fanxuan">全选/反选</button>
                                <script>
                                    $(document).ready(function() {
                                        $("#fanxuan").on("click", function() {
                                            $("#tablexx td :checkbox").each(function() {
                                                $(this).prop("checked", !$(this).prop("checked"));
                                            });
                                        });
                                    });
                                </script>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-sm btn-danger" id="delete">删除</button>
                                <script>
                                    $(document).ready(function() {
                                        $("#delete").bind('click', function() {
                                            var ids = [];
                                            $.each($('#tablexx input:checkbox:checked'), function() {
                                                ids.push($(this).val());
                                            });
                                            deleteMessage(ids.join(','));
                                        });
                                    });
                                </script>
                            </div>
                        </form>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    <nav class="mb-3">
        <ul class="pagination">
            {foreach $pages as $v}
            {if $v=='...'}
            <li class="page-item disabled"><a class="page-link" href="javascript:void(0);">{$v}</a></li>
            {elseif isset($v['current'])}
            <li class="page-item active"><a class="page-link" href="javascript:void(0);">{$v.page}</a></li>
            {else}
            <li class="page-item"><a class="page-link" href="{echo $router->build('/ebcms/ucenter-web/message', array_merge($_GET, ['page'=>$v['page']]))}">{$v.page}</a></li>
            {/if}
            {/foreach}
        </ul>
    </nav>
</div>
{include common/footer@ebcms/ucenter-web}