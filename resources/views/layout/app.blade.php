<!DOCTYPE HTML>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="cache-control" content="max-age=0" />
<meta http-equiv="expires" content="-1" />
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="cache-control" content="no-cache" />

<title>Color Meter Nutricell</title>
<link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900,900i|Source+Sans+Pro:300,300i,400,400i,600,600i,700,700i,900,900i" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ url('styles/style.css') }}">
<link rel="stylesheet" type="text/css" href="{{ url('styles/framework.css') }}">
<link rel="stylesheet" type="text/css" href="{{ url('fonts/css/fontawesome-all.min.css') }}">  
<script type="text/javascript" src="{{ url('scripts/jquery.js') }}"></script>
<script type="text/javascript" src="{{ url('scripts/plugins.js') }}"></script>
<script type="text/javascript" src="{{ url('scripts/custom.js') }}"></script>
<script type="text/javascript" src="{{ url('scripts/jquery.cookie.min.js') }}"></script>

@yield('css')
</head>

<body class="theme-dark" data-background="none" data-highlight="red2" onunload="deleteAllCookies()">>
        
<div id="page">

    <div id="page-preloader">
        <div class="loader-main"><div class="preload-spinner border-highlight"></div></div>
    </div>


    <div class="header header-fixed header-logo-center"> 
        <a href="{{ url('dashboard') }}" class="header-title">
          <img  style="width: 170px; margin-top: 10px" class="button button-xxs button-round-huge bg-white" src="{{ url('images/logo-header.png') }}" />
        </a>
        <a href="#" class="back-button header-icon header-icon-1"><i class="fas fa-arrow-left"></i></a>
        <a href="#" data-menu="menu-choose-pattern-color" class="header-icon header-icon-4"><i class="fas fa-cog"></i></a>
    </div>

    <!--    
    <div id="footer-menu" class="footer-menu-1-icons footer-menu-style-1">
        <a href="javascript:window.location='{{ url('dashboard')}}'" class="<?php echo Route::currentRouteName() == 'dashboard' ? 'active' : '' ?>" style="color:white"><i class="fa fa-search"></i><span>Scan Color</span></a>
        <div class="clear"></div>
    </div>
    -->

    <div class="page-content header-clear-medium">
        @yield('content')

        <div id="menu-choose-pattern-color" 
             class="menu menu-box-bottom menu-box-detached round-medium" 
             data-menu-height="400" 
             data-menu-effect="menu-over">

            <div class="">
                <h3 class="center-text uppercase ultrabold top-30">Choose Pattern Color</h3>
                <br />
                <div class="content">
                    <div class="link-list link-list-1">
                        <?php foreach ($dataColorPattern as $val): ?>                    
                          <a href="javascript:setDefaultPattern('{{ $val->id }}')">
                              <i class="fa fa-star color-yellow1-dark"></i>
                              <span><?php echo $val->name ?></span>
                              <em class="bg-highlight color_pattern_tag" style="display: none" id="color_pattern_tag_{{ $val->id }}" >DEFAULT</em>
                              <i class="fa fa-angle-right"></i>
                          </a>
                        <?php endforeach; ?>  
                        <script type="text/javascript">
                          $( document ).ready(function() {
                             setDefaultPatternFirst({{ $val->id }})
                          });   
                        </script>  
                    </div>
                </div>
                      
                <div class="content">
                    <div class="demo-buttons" style="align-content: center;">
                        <a href="{{ url('dashboard') }}" class="button button-xxs shadow-small button-round-small bg-red1-light" style="width: 45%">
                           <i class="fa fa-home"></i>&nbsp;&nbsp;Dashboard
                        </a>
                        <a href="{{ url('colorScan') }}" class="button button-xxs shadow-small button-round-small bg-blue1-light" style="width: 45%">
                           <i class="fa fa-search"></i>&nbsp;&nbsp;New Colors
                        </a>

                        <a href="#" id="btn-close-choose_pattern_color" class="close-menu button button-xxs shadow-small button-round-small bg-red1-light round-small;" style="width: 100%; text-align: center; display: none">Close</a>
                    </div>
                </div>   
            </div>
        </div>     

    </div>    
</div>

<script type="text/javascript">
    function deleteAllCookies() {
        var cookies = document.cookie.split(";");

        for (var i = 0; i < cookies.length; i++) {
            var cookie = cookies[i];
            var eqPos = cookie.indexOf("=");
            var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
            document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
        }
    }


    function setDefaultPattern(patternId) {
      //expire 30 day
      $.cookie("pattern_color_id", patternId, { expires: 30, path: '/' });
      //alert($.cookie("pattern_color_id"));
      $('.color_pattern_tag').hide();
      $('#color_pattern_tag_'+patternId).show();
      $('#btn-close-choose_pattern_color').click();
      $('#btn-close-choose_pattern_color').trigger('click');
    }

    function setDefaultPatternFirst(patternId) {
      //expire 30 day
      if(!$.cookie("pattern_color_id")) {
        $.cookie("pattern_color_id", patternId, { expires: 30, path: '/' });
        $('.color_pattern_tag').hide();
        $('#color_pattern_tag_'+patternId).show();        
      } else  {
        $('.color_pattern_tag').hide();
        $('#color_pattern_tag_'+$.cookie("pattern_color_id")).show();                
      }
    }



    function cleareCache() {
    }

    cleareCache()
</script>

@yield('js')

</body>
</html>
