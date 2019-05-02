<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if(!GMW::isUserValid()) {
    echo "<h3>Only Administor can access this page.</h3>";
}

if(!empty($_POST['gmw-bl-rule'])) {
    $add_res = GMWActions::addRule();
}

$gmw_rules = GMWActions::getRules();
$GMW_enabled = get_option('gmw-enabled');

if($GMW_enabled === 'no') {
    $GMW_enable_btn_str = 'Enable Whitelist';
    $GMW_enable_btn_data = '1';
    $GMW_enable_btn_color = "red";
} else if($GMW_enabled === 'yes') {
    $GMW_enable_btn_str = 'Disable Whitelist';
    $GMW_enable_btn_data = '0';
    $GMW_enable_btn_color = "green";
} else {
    $GMW_enable_btn_str = 'Error Status';
}

$gmw_ajax_nonce = wp_create_nonce('gmw_ajax');

?>
<form method="post" class="gmw-add-form">
<div>
    <h3>Emails to Allow(Support Regular Expression):</h3>
    <h4>Emails not on the whitelist will not be allowed to register.</h4>
    <div class="gmw-instruct">
    <strong>Instruction</strong>: 
    <ul>
    <li>When adding regular expressions, please wrap it with symbol '/'. For example: /.*@a.com/ means allow all emails with the domain a.com. Any rule without wrapping by '/' will be regarded as a full match rule.</li>
    <br>
    <li>This whitelist function relys on the default WordPress registration process. So if you are using any customized registration pages, please make sure they follow the WordPress standard registration functions and process.</li>
    <br>
    <li>Please notice that if you enable the whitelist but don't add any rule, no one will be allowed to register.</li>
    </ul>
    </div>
</div>
<br/>
<div>
    <input type="text" name="gmw-bl-rule" placeholder="One rule at a time" style="width: 500px"/>
    <?php wp_nonce_field( 'gmw_form', 'gmw-form-nonce' ); ?>
    <input type="submit" value="Add"/>
    <?php if(!$add_res['res']):?>
    <div style="color:red">
        <strong><?php echo esc_html($add_res['info']);?></strong>
    </div>
    <?php endif;?>
</div>
</form>

<div class="gmw-enable-session" style="font-size:large;">
    <span style="font-weight: bold;">Whitelist Enabled:</span>
    <span style="background-color:<?php echo esc_attr($GMW_enable_btn_color);?>;padding:2px;color:white;" ><?php echo esc_html(strtoupper($GMW_enabled));?></span>
    <button data="<?php echo esc_attr($GMW_enable_btn_data);?>" style="font-size:medium;padding:5px" id="gmw-enable-btn"><?php echo esc_html($GMW_enable_btn_str);?></button>
</div>

<table class="gmw-rules-tb">
<tr>
    <th>Rules</th>
    <th>Created Time</th>
    <th>By</th>
    <th>Action</th>
</tr>

<?php if(!empty($gmw_rules)):?>
<?php foreach($gmw_rules as $rule):?>
<tr>
    <td><?php echo esc_html($rule['expression']);?></td>
    <td><?php echo esc_html($rule['time']);?></td>
    <?php $user = get_user_by('id', $rule['userid']);?>
    <td><?php echo esc_html($user->display_name);?></td>
    <td><button class="gmw-del-btn" data="<?php echo esc_attr($rule['id']);?>">Delete</button></td>
</tr>
<?php endforeach;?>
<?php endif;?>
</table>

<script type="text/javascript">
var delBtns = document.querySelectorAll('.gmw-del-btn');
var enableBtn = document.querySelector('#gmw-enable-btn');

function post(action, data) {
    var jsonDat = {
        action: action, 
        data: data,
        _ajax_nonce: "<?php echo esc_js($gmw_ajax_nonce);?>",
    }

    jQuery.ajax({
        url: ajaxurl,
        data: jsonDat,
        type: "POST",
        dataType: "json",
        success: function(res) {
            location.href = location.href;
        }, 
    });
}

if(typeof delBtns != 'undefined' && delBtns.length > 0) {
    delBtns.forEach(function(btn, idx) {
        btn.addEventListener('click', function(ev) {
            if(confirm('Are you sure to delete?')) {
                var ele = ev.target;
                var data = ele.getAttribute('data');

                post('gmw_del', data);
            };
        });
    });
}

if(typeof enableBtn != 'undefined') {
    enableBtn.addEventListener('click', function(ev) {
        var ele = ev.target;
        var data = ele.getAttribute('data');

        post("gmw_enable", data);
    });
}
</script>

<style type="text/css">
.gmw-enable-session {
    margin: 10px;
}

.gmw-add-form {
    margin: 10px;
}

.gmw-add-form div{
    margin-bottom: 10px;
}

.gmw-rules-tb {
    margin: 10px;
    text-align: center;
    width: 90%;
}

.gmw-rules-tb tr:nth-child(1) {
    background-color: black; 
    color: white;
}

.gmw-rules-tb td, .gmw-rules-tb th {
    padding: 5px;
}

.gmw-rules-tb tr:nth-child(2n) {
    background-color: #ccc;
}

.gmw-instruct {
    border: 1px solid orange;
    padding: 1rem;
}

.gmw-instruct ul li {
    list-style-type: square;
    margin-left: 1rem;
}
</style>
