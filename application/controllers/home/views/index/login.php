<?php
$this->extendTpl($theme_dir . '/layout.php'); ?>


<?php $this->blockStart('content'); ?>
  <div class="container">
   <div class="view-site">
    <a href="/" target="_blank"><i class="fa fa-fw fa-external-link-square"></i> 查看站点</a>
   </div>
   <div class="row">
    <div class="col-sm-12 login-block center-block">
     <img src="/static/images/bolt-logo.png?33a1f58e59" width="150" height="62" alt="Bolt" class="logo" />
     <div class="panel panel-default">
      <div class="panel-body">
       <div class="row">
       </div>
       <noscript>
        <div class="alert alert-danger">
         <p>你的浏览器当前已禁用JavaScript。Bolt的大多数功能无需它的支持，但是为何获得更好的体验我们建议你启用浏览器的JavaScript。</p>
        </div>
       </noscript>
       <form name="user_login" method="post" class="submitspinner">
        <div class="form-group">
         <label for="user_login_username" class="control-label">用户名 / email</label>
         <div class="input-group">
          <label for="user_login_username" class="input-group-addon"><i class="fa fa-fw fa-user"></i></label>
          <input id="user_login_username" name="username" class="form-control" type="text" autofocus="" placeholder="Your username …" value="" required="" />
         </div>
        </div>
        <div class="form-group password-group">
         <label for="password" class="control-label"> 密码 <span class="togglepass show-password"><i class="fa fa-fw fa-eye"></i> 显示</span> <span class="togglepass hide-password"><i class="fa fa-fw fa-eye-slash"></i> 隐藏</span> </label>
         <div class="input-group">
          <label for="user_login_password" class="input-group-addon"><i class="fa fa-fw fa-key"></i></label>
          <input id="user_login_password" name="password" class="form-control" type="password" placeholder="你的用户名 …" required="" />
         </div>
        </div>
        <p class="login-group"> <button type="submit" id="user_login_login" name="user_login[login]" class="btn-primary btn"> <i class="fa fa-sign-in fa-fw"></i>&nbsp; 登录</button> <button type="button" id="user_login_forgot" name="user_login[forgot]" class="btn-link login-forgot btn"> 我忘记了自己的密码</button> </p>
        <p class="reset-group"> <button type="submit" id="user_login_reset" name="user_login[reset]" class="btn-primary btn"> <i class="fa fa-envelope-o"></i>&nbsp; 重设密码</button> <button type="button" id="user_login_back" name="user_login[back]" class="btn-link login-remembered btn"> 返回登录</button> </p>
        <p class="cookie-notice alert alert-danger"> 注意: 登录 Bolt 需要启用 Cookies。请打开 Cookies 功能。 </p>
        <input type="hidden" id="user_login__token" name="user_login[_token]" value="6Q3_zvvAr3Z8spfB43wIxDWhz9eRRXYjKxhZJrpj3MI" />
       </form>
      </div>
     </div>
     <blockquote class="quote">
      “Focus and simplicity. Simple can be harder than complex: You have to work hard to get your thinking clean to make it simple. But it's worth it in the end because once you get there, you can move mountains.”
      <cite>— Steve Jobs</cite>
     </blockquote>
    </div>
   </div>
  </div>
<?php $this->blockEnd(); ?>


<?php $this->blockStart('scripts'); ?>
<script src="/bolt-public/js/bolt.js?1721f6ee98" data-config="{&quot;locale&quot;:{&quot;short&quot;:&quot;zh&quot;,&quot;long&quot;:&quot;zh_CN&quot;},&quot;timezone&quot;:{&quot;offset&quot;:&quot;+00:00&quot;},&quot;buid&quot;:&quot;buid-1&quot;,&quot;uploadConfig&quot;:{&quot;url&quot;:&quot;\/bolt\/files&quot;,&quot;acceptFilesTypes&quot;:[&quot;twig&quot;,&quot;html&quot;,&quot;js&quot;,&quot;css&quot;,&quot;scss&quot;,&quot;gif&quot;,&quot;jpg&quot;,&quot;jpeg&quot;,&quot;png&quot;,&quot;ico&quot;,&quot;zip&quot;,&quot;tgz&quot;,&quot;txt&quot;,&quot;md&quot;,&quot;doc&quot;,&quot;docx&quot;,&quot;pdf&quot;,&quot;epub&quot;,&quot;xls&quot;,&quot;xlsx&quot;,&quot;ppt&quot;,&quot;pptx&quot;,&quot;mp3&quot;,&quot;ogg&quot;,&quot;wav&quot;,&quot;m4a&quot;,&quot;mp4&quot;,&quot;m4v&quot;,&quot;ogv&quot;,&quot;wmv&quot;,&quot;avi&quot;,&quot;webm&quot;,&quot;svg&quot;],&quot;maxSize&quot;:8346664.96,&quot;maxSizeNice&quot;:&quot;7.96 MiB&quot;},&quot;ckeditor&quot;:{&quot;lang&quot;:&quot;zh&quot;,&quot;images&quot;:0,&quot;styles&quot;:0,&quot;tables&quot;:0,&quot;anchor&quot;:0,&quot;fontcolor&quot;:0,&quot;align&quot;:0,&quot;subsuper&quot;:0,&quot;embed&quot;:0,&quot;blockquote&quot;:0,&quot;ruler&quot;:0,&quot;strike&quot;:0,&quot;underline&quot;:0,&quot;codesnippet&quot;:0,&quot;specialchar&quot;:0,&quot;ck&quot;:{&quot;autoParagraph&quot;:true,&quot;contentsCss&quot;:[&quot;\/bolt-public\/css\/ckeditor-contents.css?d9e9ff3e46&quot;,&quot;\/bolt-public\/css\/ckeditor.css?f47046d0e4&quot;],&quot;filebrowserWindowWidth&quot;:640,&quot;filebrowserWindowHeight&quot;:480,&quot;disableNativeSpellChecker&quot;:true,&quot;allowNbsp&quot;:false},&quot;filebrowser&quot;:{&quot;browseUrl&quot;:&quot;\/async\/recordbrowser&quot;,&quot;imageBrowseUrl&quot;:&quot;\/bolt\/files&quot;}},&quot;stackAddUrl&quot;:&quot;\/async\/stack\/add&quot;,&quot;contentActionUrl&quot;:&quot;\/async\/content\/action&quot;,&quot;google_api_key&quot;:null}" data-jsdata="[]"></script>
<?php $this->blockEnd(); ?>
