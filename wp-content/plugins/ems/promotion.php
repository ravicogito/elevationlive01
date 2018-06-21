<style type='text/css'>
    .base-markup{ padding:20px; box-sizing:border-box; background-color:#fff; margin-bottom: 30px;}
</style>

<div class='wrap'>
    <?php global $Quote; ?>
    <h1>Promotions Code</h1>
    <div class='markup-panel'>		
        <h2>Add New Promotion Code:</h2>		
        <form method='post'>		
            <input type='hidden' name='ems_cmd' value='add_promo' />		
            <table class='widefat'>			
                <thead>				
                    <tr>
                        <th>CODE</th>
                        <th>Discount (%)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th><input type='text' class='ibox' name='newpromotion[code]' /></th>
                        <th><input type='text' class='ibox' name='newpromotion[discount]' /></th>                        
                    </tr>
                </tbody>
            </table><br>
            <input type='submit' class='button-primary' value='Add' />
        </form>
    </div>

    <div class='current-markups'>
        <h2>All Promotion Code:</h2>
        <?php if (isset($_GET['u'])): ?>
            <div style='padding:20px; background-color:#fff; margin:10px 0; font-size:20px; color:#1CAA3D;'>Promotion updated!</div>
        <?php endif; ?>
        <table class='widefat'>
            <thead>
                <tr>
                    <th>CODE</th>
                    <th>Discount (%)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>CODE</th>
                    <th>Discount (%)</th>
                    <th>Action</th>
                </tr>
            </tfoot>
            <tbody>
                <?php $promocodes = $Quote->get_promocodes();
                foreach ($promocodes as $code):
                    ?>
                    <tr>
                        <td><input type='text' class='default-code' value='<?php echo $code->code;?>'/></td>
                        <td><input type='text' class='default-dicount' value='<?php echo $code->discount;?>'/></td>
                        <td>
                            <a class='button-primary save-promocodes' href='Javascript:void(0);' data-id='<?php echo $code->id; ?>'>Update</a>
                            <a class='button-secondary delete-promocode' href='admin.php?page=ems_promotion&ems_cmd=delete_promocode&promocode_id=<?php echo $code->id; ?>'>Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script type='text/javascript'>
        $ = jQuery;

        $(document).ready(function () {
            $('.delete-promocode').click(function () {
                var c = confirm('Are you sure you want to permanently delete this Promo price?');
                if (c === true) {
                    return true;
                } else {
                    return false;
                }
            });

            $('.save-promocodes').click(function () {
                var id      = $(this).attr('data-id');
                var code    = $('.default-code', $(this).parent().parent()).val();
                var dicount = $('.default-dicount', $(this).parent().parent()).val();

                var str         = 'admin.php?page=ems_promotion&ems_cmd=update_promotion&code=' + code + '&dicount=' + dicount;
                str += '&promo_id='+id;
                window.location = str;
            });
        });
    </script>
</div>