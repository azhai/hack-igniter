<?php
$this->extendTpl(__DIR__ . '/panel.php'); ?>


<?php $this->blockStart('content'); ?>
   <div id="navpage-content" class="container-fluid">
    <div class="row">
     <div class="col-xs-12">
      <h1 class="page-header"> <strong>概览 &raquo;</strong> <i style="font-style: normal;"> Entries </i> </h1>
     </div>
    </div>
    <div class="row">
     <div class="col-md-8">
      <noscript>
       <div class="alert alert-danger">
        <p>浏览器当前已禁用JavaScript。为获得更好的体验，我们建议您启用它。</p>
       </div>
      </noscript>
      <div class="buic-listing" data-bolt-widget="buicListing" data-contenttype="entries" data-contenttype-name="Entry" data-bolt_csrf_token="NXbzul4hCWjla59YIYIp-npTSTUdXhODd2424bybboE">
       <table class=" dashboardlisting listing">
        <tbody class="sortable striping_odd" data-bolt-widget="buicListingPart">
         <tr class="header">
          <th class="menu hidden-xs">
           <div class="btn-group">
            <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <span class="caret"></span> </button>
            <ul class="dropdown-menu">
             <li class="select-all"><a href="#">全选</a></li>
             <li class="hidden select-none"><a href="#">取消选择</a></li>
             <li class="hidden divider" role="separator"></li>
             <li class="hidden dropdown-header">Selection (#):</li>
             <li class="hidden"><a href="#">• Delete</a></li>
             <li class="hidden disabled"><a href="#">• Publish</a></li>
             <li class="hidden disabled"><a href="#">• Depublish</a></li>
            </ul>
           </div> </th>
          <th class="hidden-xs"> <a href="?order=id" class="order-none">Id</a> </th>
          <th style="width:80%"> <a href="?order=title" class="order-none">标题 / 摘要</a> </th>
          <th class="listthumb"></th>
          <th class="username hidden-sm hidden-xs"> <a href="?order=datecreated" class="order-none">元</a> </th>
          <th><span class="hidden-xs">操作</span></th>
         </tr>

        <?php foreach ($page_rows as $row): ?>
         <tr id="item_<?= $row['id'] ?>"<?= ('published' === $row['status']) ? '' : ' class="dim"'?>>
          <td class="check hidden-xs"><input type="checkbox" name="checkRow" /></td>
          <td class="id hidden-xs">№ <?= $row['id'] ?></td>
          <td class="excerpt large">
            <span>
              <strong class="visible-xs">№ <?= $row['id'] ?>. </strong>
              <strong> <a href="<?= $edit_url .'?id='. $row['id'] ?>" title="Slug: <?= $row['slug'] ?>"> <?= $row['title'] ?></a> </strong>
               <?= $row['teaser'] ?> …
            </span>
          </td>
          <td class="listthumb">
            <a href="<?= $static_url ?>/uploads/<?= $row['image']['file'] ?>" class="magnific" title="图像: Garden Gardening Grass 589.">
              <img src="<?= $static_url ?>//thumbs/<?= $row['image']['file'] ?>" width="80" height="60" alt="Thumbnail" />
            </a>
          </td>
          <td class="username hidden-sm hidden-xs">
            <i class="fa fa-user fa-fw"></i>
            <?= $row['owner']['displayname'] ?> <br />
            <i class="fa fa-circle status-<?= $row['status'] ?> fa-fw" title="<?= $row['status_title'] ?>"></i> 2017/11/14<br />
            <i class="fa fa-align-left fa-fw"></i>
            <a href="<?= $edit_url .'?id='. $row['id'] ?>#taxonomy"> 排序：0 </a>
          </td>
          <td class="actions">
           <div class="btn-group">
            <a class="btn btn-default btn-xs hidden-xs" href="<?= $edit_url .'?id='. $row['id'] ?>"> <i class="fa fa-edit"></i> 编辑 </a>
            <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown"> <i class="fa fa-info-sign"></i> <span class="caret"></span> </button>
            <ul class="dropdown-menu pull-right">
             <li> <a href="/entry/graece-donan-latine-voluptatem-vocant" target="_blank"> <i class="fa fa-external-link-square"></i> 在站点上观看 </a> </li>
             <li> <a href="#" data-listing-cmd="record:depublish"><i class="fa fa-circle status-held"></i> 将状态更改为 '保留'</a> </li>
             <li> <a href="#" data-listing-cmd="record:draft"><i class="fa fa-circle status-draft"></i> 将状态更改为 '草稿'</a> </li>
             <li> <a href="/bolt/editcontent/entries?source=<?= $row['id'] ?>&amp;duplicate=1"> <i class="fa fa-copy"></i> 复制Entry </a> </li>
             <li> <a href="#" data-listing-cmd="record:delete"><i class="fa fa-trash"></i> 删除Entry</a> </li>
             <li class="divider"></li>
             <li> <a class="nolink"> 作者： <strong><i class="fa fa-user"></i> <?= $row['owner']['displayname'] ?> </strong> </a> </li>
             <li> <a class="nolink">当前状态：<strong>published</strong></a> </li>
             <li> <a class="nolink">Slug: <code title="graece-donan-latine-voluptatem-vocant">graece-donan-latine-volu</code> </a> </li>
             <li> <a class="nolink">创建于： <i class="fa fa-asterisk"></i> 2018-01-20 01:07 </a> </li>
             <li> <a class="nolink">发表于： <i class="fa fa-calendar"></i> 2017-11-14 13:17 </a> </li>
             <li> <a class="nolink">上一次编辑于: <i class="fa fa-refresh"></i> 2018-02-25 10:46 </a> </li>
             <li> <a class="nolink">Category: <i class="fa fa-tag"></i> love </a> </li>
             <li> <a class="nolink">Tags: <i class="fa fa-tag"></i> world, technology, drugs </a> </li>
            </ul>
           </div>
           </td>
         </tr>
        <?php endforeach; ?>

         <tr class="selectiontoolbar hidden">
          <td colspan="6"> <i class="fa fa-fw fa-rotate-270 fa-mail-forward"></i>
           <div class="count">
            0
           </div> <button type="button" data-stb-cmd="record:delete" class="danger separator"><i class="fa fa-trash"></i> Delete</button> <button type="button" data-stb-cmd="record:publish"><i class="fa fa-circle status-published"></i> Publish</button> <button type="button" data-stb-cmd="record:depublish"><i class="fa fa-circle status-held"></i> Depublish</button> <button type="button" data-stb-cmd="record:draft"><i class="fa fa-circle status-draft"></i> 草稿</button> </td>
         </tr>
        </tbody>
       </table>
       <div style="text-align: center">
        <ul class="pagination pagination-centered">
         <li><span>显示 35 条记录中的 1 到 10 条entries记录</span> </li>
         <li class="active" aria-current="true"> <a><i class="fa fa-angle-double-left" style="font-weight: bold;"></i> 1</a> </li>
         <li> <a href="?&amp;page_entries=2" rel="noindex, follow">2</a> </li>
         <li> <a href="?&amp;page_entries=3" rel="noindex, follow">3</a> </li>
         <li> <a href="?&amp;page_entries=4" rel="noindex, follow">4 <i class="fa fa-angle-double-right" style="font-weight: bold;"></i></a> </li>
         <li> <a href="?&amp;page_entries=2" rel="noindex, follow"><i class="fa fa-angle-right" style="font-weight: bold;"></i></a> </li>
        </ul>
       </div>
      </div>
     </div>

     <aside class="col-md-4">
      <div class="panel panel-default panel-news">
       <div class="panel-heading">
        <i class="fa fa-fw fa-cog"></i> 对Entries的操作
       </div>
       <div class="panel-body">
        <a class="btn btn-primary" href="/bolt/editcontent/entries"> <i class="fa fa-plus"></i> 新建Entry </a>
        <p style="margin-top: 15px;"><strong>筛选</strong></p>
        <form class="form-inline">
         <div class="form-group">
          <select name="taxonomy-categories" class="form-control"> <option value=""> (Categories) </option> <option value="news"> news </option> <option value="events"> events </option> <option value="movies"> movies </option> <option value="music"> music </option> <option value="books"> books </option> <option value="life"> life </option> <option value="love"> love </option> <option value="fun"> fun </option> </select> or
          <input type="text" class="form-control" value="" name="filter" style="width: 110px;" placeholder="Keyword …" />
         </div>
         <div class="form-group" style="display: block; margin-top: 12px;">
          <button type="submit" class="btn btn-tertiary"><i class="fa fa-filter"></i> 筛选</button>
         </div>
        </form>
        <div class="description">
        </div>
        <p><strong>详情</strong></p>
        <ul>
         <li>默认状态: published</li>
         <li>列表页模板: <code>listing.twig</code></li>
         <li>详情页模板: <code>entry.twig</code></li>
         <li>分类: categories, tags</li>
         <li> 关系: pages </li>
        </ul>
       </div>
      </div>
      <div class="panel panel-default panel-lastmodified">
       <div class="panel-heading">
        <i class="fa fa-fw fa-clock-o"></i> 最近修改的Entries
       </div>
       <div class="panel-body">
        <ul>
         <li> № 35. <a href="<?= $edit_url .'?id='. 35 ?>">ALIO MODO.</a> <small>(<time class="buic-moment" data-bolt-widget="buicMoment" datetime="2018-02-25T10:49:13+00:00">2018-02-25T10:49:13+00:00</time>)</small> </li>
         <li> № 34. <a href="<?= $edit_url .'?id='. 34 ?>">Nescio quo modo praetervolavit oratio.</a> <small>(<time class="buic-moment" data-bolt-widget="buicMoment" datetime="2018-02-25T10:49:10+00:00">2018-02-25T10:49:10+00:00</time>)</small> </li>
         <li> № 33. <a href="<?= $edit_url .'?id='. 33 ?>">Sin aliud quid voles, postea.</a> <small>(<time class="buic-moment" data-bolt-widget="buicMoment" datetime="2018-02-25T10:49:08+00:00">2018-02-25T10:49:08+00:00</time>)</small> </li>
         <li> № 32. <a href="<?= $edit_url .'?id='. 32 ?>">Quid enim est a Chrysippo praetermissum in Stoicis?</a> <small>(<time class="buic-moment" data-bolt-widget="buicMoment" datetime="2018-02-25T10:49:03+00:00">2018-02-25T10:49:03+00:00</time>)</small> </li>
         <li> № 31. <a href="<?= $edit_url .'?id='. 31 ?>">Immo videri fortasse.</a> <small>(<time class="buic-moment" data-bolt-widget="buicMoment" datetime="2018-02-25T10:49:00+00:00">2018-02-25T10:49:00+00:00</time>)</small> </li>
        </ul>
       </div>
      </div>
     </aside>

    </div>
   </div>
<?php $this->blockEnd(); ?>

<?php $this->blockStart('scripts'); ?>
<script src="<?= $static_url ?>/js/bolt.js?1721f6ee98" data-config="{&quot;locale&quot;:{&quot;short&quot;:&quot;zh&quot;,&quot;long&quot;:&quot;zh_CN&quot;},&quot;timezone&quot;:{&quot;offset&quot;:&quot;+00:00&quot;},&quot;buid&quot;:&quot;buid-1&quot;,&quot;uploadConfig&quot;:{&quot;url&quot;:&quot;\/bolt\/files&quot;,&quot;acceptFilesTypes&quot;:[&quot;twig&quot;,&quot;html&quot;,&quot;js&quot;,&quot;css&quot;,&quot;scss&quot;,&quot;gif&quot;,&quot;jpg&quot;,&quot;jpeg&quot;,&quot;png&quot;,&quot;ico&quot;,&quot;zip&quot;,&quot;tgz&quot;,&quot;txt&quot;,&quot;md&quot;,&quot;doc&quot;,&quot;docx&quot;,&quot;pdf&quot;,&quot;epub&quot;,&quot;xls&quot;,&quot;xlsx&quot;,&quot;ppt&quot;,&quot;pptx&quot;,&quot;mp3&quot;,&quot;ogg&quot;,&quot;wav&quot;,&quot;m4a&quot;,&quot;mp4&quot;,&quot;m4v&quot;,&quot;ogv&quot;,&quot;wmv&quot;,&quot;avi&quot;,&quot;webm&quot;,&quot;svg&quot;],&quot;maxSize&quot;:8346664.96,&quot;maxSizeNice&quot;:&quot;7.96 MiB&quot;},&quot;ckeditor&quot;:{&quot;lang&quot;:&quot;zh&quot;,&quot;images&quot;:0,&quot;styles&quot;:0,&quot;tables&quot;:0,&quot;anchor&quot;:0,&quot;fontcolor&quot;:0,&quot;align&quot;:0,&quot;subsuper&quot;:0,&quot;embed&quot;:0,&quot;blockquote&quot;:0,&quot;ruler&quot;:0,&quot;strike&quot;:0,&quot;underline&quot;:0,&quot;codesnippet&quot;:0,&quot;specialchar&quot;:0,&quot;ck&quot;:{&quot;autoParagraph&quot;:true,&quot;contentsCss&quot;:[&quot;\/bolt-public\/css\/ckeditor-contents.css?d9e9ff3e46&quot;,&quot;\/bolt-public\/css\/ckeditor.css?f47046d0e4&quot;],&quot;filebrowserWindowWidth&quot;:640,&quot;filebrowserWindowHeight&quot;:480,&quot;disableNativeSpellChecker&quot;:true,&quot;allowNbsp&quot;:false},&quot;filebrowser&quot;:{&quot;browseUrl&quot;:&quot;\/async\/recordbrowser&quot;,&quot;imageBrowseUrl&quot;:&quot;\/bolt\/files&quot;}},&quot;stackAddUrl&quot;:&quot;\/async\/stack\/add&quot;,&quot;contentActionUrl&quot;:&quot;\/async\/content\/action&quot;,&quot;google_api_key&quot;:null}" data-jsdata="{&quot;omnisearch&quot;:{&quot;placeholder&quot;:&quot;\u67e5\u627e&quot;},&quot;recordlisting&quot;:{&quot;confirm&quot;:{&quot;one&quot;:&quot;Are you sure you want to do this for 1 record?&quot;,&quot;multi&quot;:&quot;Are you sure you want to do this for %NUMBER% records?&quot;,&quot;no-undo&quot;:&quot;There is no undo!&quot;},&quot;action&quot;:{&quot;cancel&quot;:&quot;\u53d6\u6d88&quot;,&quot;delete&quot;:&quot;\u5220\u9664%CTNAME%&quot;,&quot;publish&quot;:&quot;\u53d1\u5e03%CTNAME%&quot;,&quot;depublish&quot;:&quot;\u5c06\u72b6\u6001\u66f4\u6539\u4e3a '\u4fdd\u7559'&quot;,&quot;draft&quot;:&quot;\u5c06\u72b6\u6001\u66f4\u6539\u4e3a '\u8349\u7a3f'&quot;}}}"></script>
  <div id="sfwdt0ef09d" class="sf-toolbar" style="display: none"></div>
  <script>/*<![CDATA[*/        Sfjs = (function() {        "use strict";        var classListIsSupported = 'classList' in document.documentElement;        if (classListIsSupported) {            var hasClass = function (el, cssClass) { return el.classList.contains(cssClass); };            var removeClass = function(el, cssClass) { el.classList.remove(cssClass); };            var addClass = function(el, cssClass) { el.classList.add(cssClass); };            var toggleClass = function(el, cssClass) { el.classList.toggle(cssClass); };        } else {            var hasClass = function (el, cssClass) { return el.className.match(new RegExp('\\b' + cssClass + '\\b')); };            var removeClass = function(el, cssClass) { el.className = el.className.replace(new RegExp('\\b' + cssClass + '\\b'), ' '); };            var addClass = function(el, cssClass) { if (!hasClass(el, cssClass)) { el.className += " " + cssClass; } };            var toggleClass = function(el, cssClass) { hasClass(el, cssClass) ? removeClass(el, cssClass) : addClass(el, cssClass); };        }        var noop = function() {},            collectionToArray = function (collection) {                var length = collection.length || 0,                    results = new Array(length);                while (length--) {                    results[length] = collection[length];                }                return results;            },            profilerStorageKey = 'sf2/profiler/',            request = function(url, onSuccess, onError, payload, options) {                var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');                options = options || {};                options.maxTries = options.maxTries || 0;                xhr.open(options.method || 'GET', url, true);                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');                xhr.onreadystatechange = function(state) {                    if (4 !== xhr.readyState) {                        return null;                    }                    if (xhr.status == 404 && options.maxTries > 1) {                        setTimeout(function(){                            options.maxTries--;                            request(url, onSuccess, onError, payload, options);                        }, 500);                        return null;                    }                    if (200 === xhr.status) {                        (onSuccess || noop)(xhr);                    } else {                        (onError || noop)(xhr);                    }                };                xhr.send(payload || '');            },            getPreference = function(name) {                if (!window.localStorage) {                    return null;                }                return localStorage.getItem(profilerStorageKey + name);            },            setPreference = function(name, value) {                if (!window.localStorage) {                    return null;                }                localStorage.setItem(profilerStorageKey + name, value);            },            requestStack = [],            extractHeaders = function(xhr, stackElement) {                /* Here we avoid to call xhr.getResponseHeader in order to */                /* prevent polluting the console with CORS security errors */                var allHeaders = xhr.getAllResponseHeaders();                var ret;                if (ret = allHeaders.match(/^x-debug-token:\s+(.*)$/im)) {                    stackElement.profile = ret[1];                }                if (ret = allHeaders.match(/^x-debug-token-link:\s+(.*)$/im)) {                    stackElement.profilerUrl = ret[1];                }            },            renderAjaxRequests = function() {                var requestCounter = document.querySelectorAll('.sf-toolbar-ajax-requests');                if (!requestCounter.length) {                    return;                }                var ajaxToolbarPanel = document.querySelector('.sf-toolbar-block-ajax');                var tbodies = document.querySelectorAll('.sf-toolbar-ajax-request-list');                var state = 'ok';                if (tbodies.length) {                    var tbody = tbodies[0];                    var rows = document.createDocumentFragment();                    if (requestStack.length) {                        for (var i = 0; i < requestStack.length; i++) {                            var request = requestStack[i];                            var row = document.createElement('tr');                            rows.insertBefore(row, rows.firstChild);                            var methodCell = document.createElement('td');                            if (request.error) {                                methodCell.className = 'sf-ajax-request-error';                            }                            methodCell.textContent = request.method;                            row.appendChild(methodCell);                            var pathCell = document.createElement('td');                            pathCell.className = 'sf-ajax-request-url';                            if ('GET' === request.method) {                                var pathLink = document.createElement('a');                                pathLink.setAttribute('href', request.url);                                pathLink.textContent = request.url;                                pathCell.appendChild(pathLink);                            } else {                                pathCell.textContent = request.url;                            }                            pathCell.setAttribute('title', request.url);                            row.appendChild(pathCell);                            var durationCell = document.createElement('td');                            durationCell.className = 'sf-ajax-request-duration';                            if (request.duration) {                                durationCell.textContent = request.duration + "ms";                            } else {                                durationCell.textContent = '-';                            }                            row.appendChild(durationCell);                            row.appendChild(document.createTextNode(' '));                            var profilerCell = document.createElement('td');                            if (request.profilerUrl) {                                var profilerLink = document.createElement('a');                                profilerLink.setAttribute('href', request.profilerUrl);                                profilerLink.textContent = request.profile;                                profilerCell.appendChild(profilerLink);                            } else {                                profilerCell.textContent = 'n/a';                            }                            row.appendChild(profilerCell);                            var requestState = 'ok';                            if (request.error) {                                requestState = 'error';                                if (state != "loading" && i > requestStack.length - 4) {                                    state = 'error';                                }                            } else if (request.loading) {                                requestState = 'loading';                                state = 'loading';                            }                            row.className = 'sf-ajax-request sf-ajax-request-' + requestState;                        }                        var infoSpan = document.querySelectorAll(".sf-toolbar-ajax-info")[0];                        var children = collectionToArray(tbody.children);                        for (var i = 0; i < children.length; i++) {                            tbody.removeChild(children[i]);                        }                        tbody.appendChild(rows);                        if (infoSpan) {                            var text = requestStack.length + ' AJAX request' + (requestStack.length > 1 ? 's' : '');                            infoSpan.textContent = text;                        }                        ajaxToolbarPanel.style.display = 'block';                    } else {                        ajaxToolbarPanel.style.display = 'none';                    }                }                requestCounter[0].textContent = requestStack.length;                var className = 'sf-toolbar-ajax-requests sf-toolbar-value';                requestCounter[0].className = className;                if (state == 'ok') {                    Sfjs.removeClass(ajaxToolbarPanel, 'sf-ajax-request-loading');                    Sfjs.removeClass(ajaxToolbarPanel, 'sf-toolbar-status-red');                } else if (state == 'error') {                    Sfjs.addClass(ajaxToolbarPanel, 'sf-toolbar-status-red');                    Sfjs.removeClass(ajaxToolbarPanel, 'sf-ajax-request-loading');                } else {                    Sfjs.addClass(ajaxToolbarPanel, 'sf-ajax-request-loading');                }            };        var addEventListener;        var el = document.createElement('div');        if (!('addEventListener' in el)) {            addEventListener = function (element, eventName, callback) {                element.attachEvent('on' + eventName, callback);            };        } else {            addEventListener = function (element, eventName, callback) {                element.addEventListener(eventName, callback, false);            };        }                    if (window.XMLHttpRequest && XMLHttpRequest.prototype.addEventListener) {                var proxied = XMLHttpRequest.prototype.open;                XMLHttpRequest.prototype.open = function(method, url, async, user, pass) {                    var self = this;                    /* prevent logging AJAX calls to static and inline files, like templates */                    var path = url;                    if (url.substr(0, 1) === '/') {                        if (0 === url.indexOf('')) {                            path = url.substr(0);                        }                    }                    else if (0 === url.indexOf('http\x3A\x2F\x2Fbolt.d.tongtong.me\x3A8080')) {                        path = url.substr(30);                    }                    if (!path.match(new RegExp("^\/bundles|^\/_wdt"))) {                        var stackElement = {                            loading: true,                            error: false,                            url: url,                            method: method,                            start: new Date()                        };                        requestStack.push(stackElement);                        this.addEventListener('readystatechange', function() {                            if (self.readyState == 4) {                                stackElement.duration = new Date() - stackElement.start;                                stackElement.loading = false;                                stackElement.error = self.status < 200 || self.status >= 400;                                extractHeaders(self, stackElement);                                Sfjs.renderAjaxRequests();                            }                        }, false);                        Sfjs.renderAjaxRequests();                    }                    proxied.apply(this, Array.prototype.slice.call(arguments));                };            }                return {            hasClass: hasClass,            removeClass: removeClass,            addClass: addClass,            toggleClass: toggleClass,            getPreference: getPreference,            setPreference: setPreference,            addEventListener: addEventListener,            request: request,            renderAjaxRequests: renderAjaxRequests,            load: function(selector, url, onSuccess, onError, options) {                var el = document.getElementById(selector);                if (el && el.getAttribute('data-sfurl') !== url) {                    request(                        url,                        function(xhr) {                            el.innerHTML = xhr.responseText;                            el.setAttribute('data-sfurl', url);                            removeClass(el, 'loading');                            (onSuccess || noop)(xhr, el);                        },                        function(xhr) { (onError || noop)(xhr, el); },                        '',                        options                    );                }                return this;            },            toggle: function(selector, elOn, elOff) {                var tmp = elOn.style.display,                    el = document.getElementById(selector);                elOn.style.display = elOff.style.display;                elOff.style.display = tmp;                if (el) {                    el.style.display = 'none' === tmp ? 'none' : 'block';                }                return this;            },            createTabs: function() {                var tabGroups = document.querySelectorAll('.sf-tabs');                /* create the tab navigation for each group of tabs */                for (var i = 0; i < tabGroups.length; i++) {                    var tabs = tabGroups[i].querySelectorAll('.tab');                    var tabNavigation = document.createElement('ul');                    tabNavigation.className = 'tab-navigation';                    for (var j = 0; j < tabs.length; j++) {                        var tabId = 'tab-' + i + '-' + j;                        var tabTitle = tabs[j].querySelector('.tab-title').innerHTML;                        var tabNavigationItem = document.createElement('li');                        tabNavigationItem.setAttribute('data-tab-id', tabId);                        if (j == 0) { Sfjs.addClass(tabNavigationItem, 'active'); }                        if (Sfjs.hasClass(tabs[j], 'disabled')) { Sfjs.addClass(tabNavigationItem, 'disabled'); }                        tabNavigationItem.innerHTML = tabTitle;                        tabNavigation.appendChild(tabNavigationItem);                        var tabContent = tabs[j].querySelector('.tab-content');                        tabContent.parentElement.setAttribute('id', tabId);                    }                    tabGroups[i].insertBefore(tabNavigation, tabGroups[i].firstChild);                }                /* display the active tab and add the 'click' event listeners */                for (i = 0; i < tabGroups.length; i++) {                    tabNavigation = tabGroups[i].querySelectorAll('.tab-navigation li');                    for (j = 0; j < tabNavigation.length; j++) {                        tabId = tabNavigation[j].getAttribute('data-tab-id');                        document.getElementById(tabId).querySelector('.tab-title').className = 'hidden';                        if (Sfjs.hasClass(tabNavigation[j], 'active')) {                            document.getElementById(tabId).className = 'block';                        } else {                            document.getElementById(tabId).className = 'hidden';                        }                        tabNavigation[j].addEventListener('click', function(e) {                            var activeTab = e.target || e.srcElement;                            /* needed because when the tab contains HTML contents, user can click */                            /* on any of those elements instead of their parent '<li>' element */                            while (activeTab.tagName.toLowerCase() !== 'li') {                                activeTab = activeTab.parentNode;                            }                            /* get the full list of tabs through the parent of the active tab element */                            var tabNavigation = activeTab.parentNode.children;                            for (var k = 0; k < tabNavigation.length; k++) {                                var tabId = tabNavigation[k].getAttribute('data-tab-id');                                document.getElementById(tabId).className = 'hidden';                                Sfjs.removeClass(tabNavigation[k], 'active');                            }                            Sfjs.addClass(activeTab, 'active');                            var activeTabId = activeTab.getAttribute('data-tab-id');                            document.getElementById(activeTabId).className = 'block';                        });                    }                }            },            createToggles: function() {                var toggles = document.querySelectorAll('.sf-toggle');                for (var i = 0; i < toggles.length; i++) {                    var elementSelector = toggles[i].getAttribute('data-toggle-selector');                    var element = document.querySelector(elementSelector);                    Sfjs.addClass(element, 'sf-toggle-content');                    if (toggles[i].hasAttribute('data-toggle-initial') && toggles[i].getAttribute('data-toggle-initial') == 'display') {                        Sfjs.addClass(element, 'sf-toggle-visible');                    } else {                        Sfjs.addClass(element, 'sf-toggle-hidden');                    }                    Sfjs.addEventListener(toggles[i], 'click', function(e) {                        e.preventDefault();                        var toggle = e.target || e.srcElement;                        /* needed because when the toggle contains HTML contents, user can click */                        /* on any of those elements instead of their parent '.sf-toggle' element */                        while (!Sfjs.hasClass(toggle, 'sf-toggle')) {                            toggle = toggle.parentNode;                        }                        var element = document.querySelector(toggle.getAttribute('data-toggle-selector'));                        Sfjs.toggleClass(element, 'sf-toggle-hidden');                        Sfjs.toggleClass(element, 'sf-toggle-visible');                        /* the toggle doesn't change its contents when clicking on it */                        if (!toggle.hasAttribute('data-toggle-alt-content')) {                            return;                        }                        if (!toggle.hasAttribute('data-toggle-original-content')) {                            toggle.setAttribute('data-toggle-original-content', toggle.innerHTML);                        }                        var currentContent = toggle.innerHTML;                        var originalContent = toggle.getAttribute('data-toggle-original-content');                        var altContent = toggle.getAttribute('data-toggle-alt-content');                        toggle.innerHTML = currentContent !== altContent ? altContent : originalContent;                    });                }            }        };    })();    Sfjs.addEventListener(window, 'load', function() {        Sfjs.createTabs();        Sfjs.createToggles();    });/*]]>*/</script>
  <script>/*<![CDATA[*/    (function () {                Sfjs.load(            'sfwdt0ef09d',            '/_profiler/wdt/0ef09d',            function(xhr, el) {                el.style.display = -1 !== xhr.responseText.indexOf('sf-toolbarreset') ? 'block' : 'none';                if (el.style.display == 'none') {                    return;                }                if (Sfjs.getPreference('toolbar/displayState') == 'none') {                    document.getElementById('sfToolbarMainContent-0ef09d').style.display = 'none';                    document.getElementById('sfToolbarClearer-0ef09d').style.display = 'none';                    document.getElementById('sfMiniToolbar-0ef09d').style.display = 'block';                } else {                    document.getElementById('sfToolbarMainContent-0ef09d').style.display = 'block';                    document.getElementById('sfToolbarClearer-0ef09d').style.display = 'block';                    document.getElementById('sfMiniToolbar-0ef09d').style.display = 'none';                }                Sfjs.renderAjaxRequests();                /* Handle toolbar-info position */                var toolbarBlocks = document.querySelectorAll('.sf-toolbar-block');                for (var i = 0; i < toolbarBlocks.length; i += 1) {                    toolbarBlocks[i].onmouseover = function () {                        var toolbarInfo = this.querySelectorAll('.sf-toolbar-info')[0];                        var pageWidth = document.body.clientWidth;                        var elementWidth = toolbarInfo.offsetWidth;                        var leftValue = (elementWidth + this.offsetLeft) - pageWidth;                        var rightValue = (elementWidth + (pageWidth - this.offsetLeft)) - pageWidth;                        /* Reset right and left value, useful on window resize */                        toolbarInfo.style.right = '';                        toolbarInfo.style.left = '';                        if (elementWidth > pageWidth) {                            toolbarInfo.style.left = 0;                        }                        else if (leftValue > 0 && rightValue > 0) {                            toolbarInfo.style.right = (rightValue * -1) + 'px';                        } else if (leftValue < 0) {                            toolbarInfo.style.left = 0;                        } else {                            toolbarInfo.style.right = '0px';                        }                    };                }                var dumpInfo = document.querySelector('.sf-toolbar-block-dump .sf-toolbar-info');                if (null !== dumpInfo) {                    Sfjs.addEventListener(dumpInfo, 'sfbeforedumpcollapse', function () {                        dumpInfo.style.minHeight = dumpInfo.getBoundingClientRect().height+'px';                    });                    Sfjs.addEventListener(dumpInfo, 'mouseleave', function () {                        dumpInfo.style.minHeight = '';                    });                }            },            function(xhr) {                if (xhr.status !== 0) {                    var sfwdt = document.getElementById('sfwdt0ef09d');                    sfwdt.innerHTML = '\                        <div class="sf-toolbarreset">\                            <div class="sf-toolbar-icon"><svg width="26" height="28" xmlns="http://www.w3.org/2000/svg" version="1.1" x="0px" y="0px" viewBox="0 0 26 28" enable-background="new 0 0 26 28" xml:space="preserve"><path fill="#FFFFFF" d="M13 0C5.8 0 0 5.8 0 13c0 7.2 5.8 13 13 13c7.2 0 13-5.8 13-13C26 5.8 20.2 0 13 0z M20 7.5 c-0.6 0-1-0.3-1-0.9c0-0.2 0-0.4 0.2-0.6c0.1-0.3 0.2-0.3 0.2-0.4c0-0.3-0.5-0.4-0.7-0.4c-2 0.1-2.5 2.7-2.9 4.8l-0.2 1.1 c1.1 0.2 1.9 0 2.4-0.3c0.6-0.4-0.2-0.8-0.1-1.3C18 9.2 18.4 9 18.7 8.9c0.5 0 0.8 0.5 0.8 1c0 0.8-1.1 2-3.3 1.9 c-0.3 0-0.5 0-0.7-0.1L15 14.1c-0.4 1.7-0.9 4.1-2.6 6.2c-1.5 1.8-3.1 2.1-3.8 2.1c-1.3 0-2.1-0.6-2.2-1.6c0-0.9 0.8-1.4 1.3-1.4 c0.7 0 1.2 0.5 1.2 1.1c0 0.5-0.2 0.6-0.4 0.7c-0.1 0.1-0.3 0.2-0.3 0.4c0 0.1 0.1 0.3 0.4 0.3c0.5 0 0.9-0.3 1.2-0.5 c1.3-1 1.7-2.9 2.4-6.2l0.1-0.8c0.2-1.1 0.5-2.3 0.8-3.5c-0.9-0.7-1.4-1.5-2.6-1.8c-0.8-0.2-1.3 0-1.7 0.4C8.4 10 8.6 10.7 9 11.1 l0.7 0.7c0.8 0.9 1.3 1.7 1.1 2.7c-0.3 1.6-2.1 2.8-4.3 2.1c-1.9-0.6-2.2-1.9-2-2.7c0.2-0.6 0.7-0.8 1.2-0.6 c0.5 0.2 0.7 0.8 0.6 1.3c0 0.1 0 0.1-0.1 0.3C6 15 5.9 15.2 5.9 15.3c-0.1 0.4 0.4 0.7 0.8 0.8c0.8 0.3 1.7-0.2 1.9-0.9 c0.2-0.6-0.2-1.1-0.4-1.2l-0.8-0.9c-0.4-0.4-1.2-1.5-0.8-2.8c0.2-0.5 0.5-1 0.9-1.4c1-0.7 2-0.8 3-0.6c1.3 0.4 1.9 1.2 2.8 1.9 c0.5-1.3 1.1-2.6 2-3.8c0.9-1 2-1.7 3.3-1.8C20 4.8 21 5.4 21 6.3C21 6.7 20.8 7.5 20 7.5z"/></svg></div>\                            An error occurred while loading the web debug toolbar. <a href="/_profiler/0ef09d">Open the web profiler.</a>\                        </div>\                    ';                    sfwdt.setAttribute('class', 'sf-toolbar sf-error-toolbar');                }            },            {'maxTries': 5}        );    })();/*]]>*/</script>
<?php $this->blockEnd(); ?>
