<?php

/**
 * @package page-loader
 * @version 1.1
 */
/*
Plugin Name: page-loader
Plugin URL: https://github.com/pantyetta/page-loader
Desxription: loading animation
Version: 1.1
Author: pantyetta
Author URL: https://pantyetta.com
*/


// 管理画面にメニュー追加
add_action('admin_menu', function(){
    
    add_menu_page(
        'Page-Loader',  // <title>に表示される
        'page-loader',  //  title
        'manage_options', //    権限 manage_options = adminだけ
        'page-loader',  // urlとして使われる文字
        'page_loader_main_page_contents', //  メニュークリック時のページ表示関数 (空白=なし)
        'dashicons-admin-users',    //  icon https://developer.wordpress.org/resource/dashicons/#awards
        65   // メニューindex 0(先頭) 5=投稿 10=固定ページ 25=コメント 60=テーマ 65=プラグイン 70=ユーザー 75=ツール 80=設定
    );
});

function page_loader_main_page_contents(){
    
    if (!current_user_can('manage_options'))
    {
      wp_die( __('この設定ページのアクセス権限がありません') );
    }

    // initialize
    $opt_bg_color_name = 'pl_bg-color';
    $opt_img_name = 'pl_img';
    $opt_bg_color_val = get_option( $opt_bg_color_name ); // 既に保存してある値があれば取得
    $opt_img_val = get_option( $opt_img_name ); // 既に保存してある値があれば取得

	$message_html = "";

    // update
    if(isset($_POST[ $opt_bg_color_name ]) || isset($_POST[ $opt_img_name ])){
        $opt_bg_color_val = $_POST[$opt_bg_color_name];
        $opt_img_val = $_POST[$opt_img_name];

        update_option($opt_bg_color_name, $opt_bg_color_val);
        update_option($opt_img_name, $opt_img_val);

        $message_html = <<<EOF
            <div class="notice notice-success is-dismissible">
                <p>保存しました。</p>
            </div>
        EOF;
    }

    echo $html =<<<EOF
    {$message_html}
    <div class="wrap">
        <h2>Page-loaderメニュー</h2>
        <form name="form1" method="post" action="">
            <p><input type="text" name="{$opt_bg_color_name}" value="{$opt_bg_color_val}" size="32" placeholder="Bg-ColorCode" /></p>
            <p><input type="text" name="{$opt_img_name}" value="{$opt_img_val}" size="32" placeholder="img-url" /></p>
            <p class="submit"><input type="submit" name="submit" class="button-primary" value="保存" /></p>
        </form>
    </div>
    EOF;
}

add_action('wp_head', function(){
    $opt_bg_color_name = 'pl_bg-color';
    $opt_img_name = 'pl_img';
    $opt_bg_color_val = get_option( $opt_bg_color_name ); // 既に保存してある値があれば取得
    $opt_img_val = get_option( $opt_img_name ); // 既に保存してある値があれば取得

    echo <<<EOF
    <style>
    .loader {
            width: 100%;
            height: 100%;
            position: fixed;
            z-index: 10000;
            background-color: {$opt_bg_color_val};
            text-align: center;
            transition: .5s;
            opacity: 100;
        }
        
        .loader-inner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        
        .loader-inner img {
            width: 12%
        }

        @media (max-width: 959px){
            .loader-inner img {
                width: 10vmax;
            }   
        }
    </style>
    <div class="loader">
        <div class="loader-inner">
            <img src="{$opt_img_val}">
        </div>
    </div>
    <script>
        window.onload = function() {
            var loader = document.getElementsByClassName("loader");
            loader[0].style.opacity = 0; 
            window.setTimeout( function() { 
                loader[0].style.display = "None"; 
            }, 500);
        }
    </script>
    EOF;
});



/*
jquery
$(window).on('load', function () {
    $(".loader").css("opacity", 0)
    setTimeout(function () {
        $(".loader").css("display", "None")
    }, 500);
});

js
window.onload = function() {
    let loader = document.getElementsByClassName(".loader");
    loader.style.opacity = 0;
    window.setTimeout( function() { 
        loader.style.display = "None"; 
    }, 500);
}

 * /