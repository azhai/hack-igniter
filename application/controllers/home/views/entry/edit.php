<?php
$this->extendTpl(__DIR__ . '/panel.php'); ?>


<?php $this->blockStart('content'); ?>
  <div id="navpage-content" class="container-fluid">
   <div class="row">
    <div class="col-xs-12">
     <h1 class="page-header"> <strong> 编辑Entry &raquo;</strong> <i style="font-style: normal;"><?= $the_row['title'] ?></i> </h1>
    </div>
   </div>
   <div class="row">
    <div class="col-md-8">
     <div class="row">
      <div class="alert alert-danger alert-dismissible">
       <button type="button" class="close" data-dismiss="alert">&times;</button> The database needs to be updated/repaired. Go to 'Configuration' &gt; '
       <a href="/bolt/dbcheck">Check Database</a>' to do this now.
      </div>
     </div>
     <noscript>
      <div class="alert alert-danger">
       <p>你的浏览器当前已禁用JavaScript。Bolt的大多数功能无需它的支持，但是为何获得更好的体验我们建议你启用浏览器的JavaScript。</p>
      </div>
     </noscript>
     <ul class="nav nav-tabs" id="filtertabs">
      <li class="active" id="tabindicator-tab-content"> <a href="#tab-content" data-toggle="tab">Content</a> </li>
      <li id="tabindicator-tab-media"> <a href="#tab-media" data-toggle="tab">Media</a> </li>
      <li id="tabindicator-tab-relations"> <a href="#tab-relations" data-toggle="tab">关系</a> </li>
      <li id="tabindicator-tab-taxonomy"> <a href="#tab-taxonomy" data-toggle="tab">分类</a> </li>
      <li id="tabindicator-tab-meta"> <a href="#tab-meta" data-toggle="tab">元</a> </li>
     </ul>
     <form name="content_edit" method="post" data-bind="{&quot;bind&quot;:&quot;editcontent&quot;,&quot;savedon&quot;:&quot;\u4fdd\u5b58\u5728\uff1a &lt;strong&gt;&lt;\/strong&gt; &lt;small&gt;(&lt;time class=\&quot;buic-moment\&quot; data-bolt-widget=\&quot;buicMoment\&quot; datetime=\&quot;2018-02-25T10:49:03+00:00\&quot;&gt;2018-02-25T10:49:03+00:00&lt;\/time&gt;)&lt;\/small&gt;&lt;\/p&gt;&quot;,&quot;newRecord&quot;:false,&quot;duplicate&quot;:false,&quot;msgNotSaved&quot;:&quot;\u65e0\u6cd5\u4fdd\u5b58entries\u3002&quot;,&quot;hasGroups&quot;:true,&quot;singularSlug&quot;:&quot;entry&quot;,&quot;previewUrl&quot;:&quot;\/preview\/entry&quot;,&quot;actionUrl&quot;:&quot;\/async\/content\/action&quot;,&quot;overviewUrl&quot;:&quot;\/bolt\/overview\/entry&quot;}" class="form-horizontal tab-content form-horizontal">
      <input type="hidden" id="content_edit__token" name="content_edit[_token]" value="1zAIsAKHQZ6G9gVdFrnjC_FbLnOhODkFdLAivC-fEDE" />
      <input name="editreferrer" id="editreferrer" type="hidden" value="" />
      <input name="contenttype" id="contenttype" type="hidden" value="entries" />
      <div class="tab-pane active" id="tab-content">
       <div data-bolt-fieldset="text">
        <fieldset class="form-group bolt-field-text" data-bolt-widget="fieldText">
         <legend class="sr-only">Title</legend>
         <label class="main control-label col-xs-12 control-label" for="title">Title: </label>
         <div class="col-xs-12">
          <input class="form-control large" id="title" maxlength="255" name="title" type="text" value="Quid enim est a Chrysippo praetermissum in Stoicis?" />
         </div>
        </fieldset>
       </div>
       <div data-bolt-fieldset="slug">
        <fieldset class="form-group bolt-field-slug" data-bolt-widget="{&quot;fieldSlug&quot;:{&quot;contentId&quot;:32,&quot;key&quot;:&quot;slug&quot;,&quot;slug&quot;:&quot;entries&quot;,&quot;uses&quot;:[&quot;title&quot;]}}">
         <legend class="sr-only">永久链接</legend>
         <label class="main col-sm-12 control-label">永久链接: </label>
         <div class="col-sm-12">
          <div class="input-group locked">
           <span class="input-group-addon">/entry/</span>
           <input class="form-control" id="slug" name="slug" readonly="readonly" type="text" value="quid-enim-est-a-chrysippo-praetermissum-in-stoicis" data-create-slug-url="/async/makeuri" />
           <div class="input-group-btn">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <span class="caret"></span> </button>
            <ul class="dropdown-menu dropdown-menu-right">
             <li class="lock disabled"> <a href="#"><i class="fa fa-fw fa-lock"></i> Lock</a> </li>
             <li class="link" style="min-width: 200px;"> <a href="#"><i class="fa fa-fw fa-link"></i> <span>Generate from: <var>Title</var></span></a> </li>
             <li class="edit"> <a href="#"><i class="fa fa-fw fa-pencil"></i> 编辑</a> </li>
            </ul>
           </div>
          </div>
         </div>
         <div class="col-sm-12 warning hidden">
          <i class="fa fa-exclamation-triangle"></i> Editing this field might break existing links to this record!
         </div>
        </fieldset>
       </div>
       <div data-bolt-fieldset="html">
        <fieldset class="form-group bolt-field-html" data-bolt-widget="fieldHtml">
         <legend class="sr-only">Teaser</legend>
         <label class="main col-xs-12" for="teaser">Teaser: </label>
         <div class="col-xs-12">
          <textarea class="form-control ckeditor" data-param="{&quot;height&quot;:&quot;150px&quot;}" id="teaser" name="teaser">&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed vobis voluptatum perceptarum recordatio vitam beatam facit, et quidem corpore perceptarum. Tu enim ista lenius, hic Stoicorum more nos vexat. At enim, qua in vita est aliquid mali, ea beata esse non potest. &lt;a href=&quot;http://loripsum.net/&quot; target=&quot;_blank&quot;&gt;Honesta oratio, Socratica, Platonis etiam.&lt;/a&gt; &lt;/p&gt;</textarea>
         </div>
        </fieldset>
       </div>
       <div data-bolt-fieldset="html">
        <fieldset class="form-group bolt-field-html" data-bolt-widget="fieldHtml">
         <legend class="sr-only">Body</legend>
         <label class="main col-xs-12" for="body">Body: </label>
         <div class="col-xs-12">
          <textarea class="form-control ckeditor" data-param="{&quot;height&quot;:&quot;300px&quot;}" id="body" name="body">&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. An me, inquam, nisi te audire vellem, censes haec dicturum fuisse? Eiuro, inquit adridens, iniquum, hac quidem de re; Sed quid minus probandum quam esse aliquem beatum nec satis beatum? Nam quibus rebus efficiuntur voluptates, eae non sunt in potestate sapientis. &lt;a href=&quot;http://loripsum.net/&quot; target=&quot;_blank&quot;&gt;Nobis aliter videtur, recte secusne, postea;&lt;/a&gt; Sin laboramus, quis est, qui alienae modum statuat industriae? &lt;b&gt;Sed haec omittamus;&lt;/b&gt; Tollenda est atque extrahenda radicitus. Duo Reges: constructio interrete. Et quidem, inquit, vehementer errat; &lt;/p&gt; &lt;ol&gt; &lt;li&gt;Quantum Aristoxeni ingenium consumptum videmus in musicis?&lt;/li&gt; &lt;li&gt;Iam illud quale tandem est, bona praeterita non effluere sapienti, mala meminisse non oportere?&lt;/li&gt; &lt;li&gt;Multa sunt dicta ab antiquis de contemnendis ac despiciendis rebus humanis;&lt;/li&gt; &lt;li&gt;Duo enim genera quae erant, fecit tria.&lt;/li&gt; &lt;li&gt;Eaedem enim utilitates poterunt eas labefactare atque pervertere.&lt;/li&gt; &lt;li&gt;Ut necesse sit omnium rerum, quae natura vigeant, similem esse finem, non eundem.&lt;/li&gt; &lt;/ol&gt; &lt;p&gt;Eaedem res maneant alio modo. Satisne ergo pudori consulat, si quis sine teste libidini pareat? &lt;a href=&quot;http://loripsum.net/&quot; target=&quot;_blank&quot;&gt;Ego vero isti, inquam, permitto.&lt;/a&gt; Nunc haec primum fortasse audientis servire debemus. Bonum negas esse divitias, praepos&igrave;tum esse dicis? &lt;a href=&quot;http://loripsum.net/&quot; target=&quot;_blank&quot;&gt;Id Sextilius factum negabat.&lt;/a&gt; Cupiditates non Epicuri divisione finiebat, sed sua satietate. Prodest, inquit, mihi eo esse animo. Non quam nostram quidem, inquit Pomponius iocans; &lt;/p&gt; &lt;ul&gt; &lt;li&gt;Cur tantas regiones barbarorum pedibus obiit, tot maria transmisit?&lt;/li&gt; &lt;li&gt;Bona autem corporis huic sunt, quod posterius posui, similiora.&lt;/li&gt; &lt;/ul&gt; &lt;p&gt;&lt;b&gt;Sed in rebus apertissimis nimium longi sumus.&lt;/b&gt; Quare conare, quaeso. Servari enim iustitia nisi a forti viro, nisi a sapiente non potest. &lt;a href=&quot;http://loripsum.net/&quot; target=&quot;_blank&quot;&gt;Et non ex maxima parte de tota iudicabis?&lt;/a&gt; &lt;a href=&quot;http://loripsum.net/&quot; target=&quot;_blank&quot;&gt;Sed in rebus apertissimis nimium longi sumus.&lt;/a&gt; &lt;/p&gt;</textarea>
         </div>
        </fieldset>
       </div>
      </div>
      <div class="tab-pane" id="tab-media">
       <div data-bolt-fieldset="image">
        <fieldset class="form-group bolt-field-image" data-bolt-widget="fieldImage">
         <legend class="sr-only">Image</legend>
         <label class="main col-xs-12 control-label control-label">Image: <i class="info-pop fa fa-info-circle" data-content="Use this field to upload a photo or image. Click the button to upload a file from your
computer, or if you're using a recent version of Chrome or Firefox, you can simply drag'n'drop the file
from your desktop or from a different browser window.&lt;br&gt;
Alternatively, you can use a previously uploaded image. To select a previously uploaded file, just
type (part) of the file name in the input area, and it will be autocompleted.
" data-html="1" data-title="Image"><span class="sr-only">信息</span></i> </label>
         <div class="col-xs-12 elm-dropzone">
          <div class="row">
           <div class="col-xs-8">
            <div class="form-group">
             <div class="col-sm-12">
              <label for="field-image">图像文件路径</label>
              <input class="form-control wide path" id="field-image" name="image[file]" placeholder="允许的文件类型为： gif, jpeg, png, ico, svg, jpg …" type="text" value="agriculture-cereals-field-621.jpg" data-autocomplete-url="/async/file/autocomplete?ext=gif%2Cjpeg%2Cpng%2Cico%2Csvg%2Cjpg" />
             </div>
            </div>
            <div class="buic-progress" data-bolt-widget="buicProgress"></div>
            <div class="button-wrap fileselectbuttongroup">
             <span class="btn btn-tertiary btn-sm fileinput-button"> <i class="fa fa-upload"></i> <span>上传图像</span> <input accept=".gif,.jpeg,.png,.ico,.svg,.jpg" data-url="/bolt/upload?handler=" id="fileupload-image" name="files[]" type="file" /> </span>
             <button class="btn btn-tertiary btn-sm" data-bolt-widget="{&quot;buicBrowser&quot;:{&quot;url&quot;:&quot;/async/browse&quot;,&quot;multiselect&quot;:false}}" type="button"><i class="fa fa-download"></i> 在服务器上选择</button>
            </div>
           </div>
           <div class="col-xs-4">
            <div class="right-on-large">
             <label>预览</label>
             <div class="content-preview imageholder">
              <img alt="Preview" data-default-url="<?= $static_url ?>/images/default_empty_4x3.png?0b29c301e1" height="150" src="/thumbs/200x150b/agriculture-cereals-field-621.jpg" width="200" />
             </div>
            </div>
           </div>
          </div>
         </div>
        </fieldset>
       </div>
       <div data-bolt-fieldset="video">
        <fieldset class="form-group bolt-field-video" data-bolt-widget="fieldVideo">
         <legend class="sr-only">Video</legend>
         <label class="main col-xs-12">Video: <i class="info-pop fa fa-info-circle" data-content="Use this field to embed a video inside a page on the website. Just copy/paste the
      URL of a video-page on Youtube, Vimeo or almost any other video sharing website.&lt;br&gt;
      Bolt will automatically fetch the &amp;lt;embed&amp;gt;-code, with the correct width, height and the original
      title. If you change the width or height, the other value will change accordingly, to maintain the
      aspect ratio." data-html="1" data-title="Video"><span class="sr-only">信息</span></i> </label>
         <div class="col-sm-8">
          <div class="form-group">
           <div class="col-sm-12">
            <label for="buid-1">要嵌入的视频URL</label>
            <div class="input-group">
             <input class="form-control url" id="buid-1" name="video[url]" placeholder="Youtube、Vimeo 或者其他视频网站的URL …" type="url" />
             <span class="input-group-btn"> <button class="btn btn-default refresh" type="button" disabled=""><i class="fa fa-refresh"></i></button> </span>
            </div>
           </div>
          </div>
          <div class="form-group">
           <label class="control-label col-sm-2">大小</label>
           <div class="col-sm-10 form-inline">
            <label for="buid-2" class="sr-only">Width</label>
            <input class="form-control width" id="buid-2" name="video[width]" type="number" /> &times;
            <label for="buid-3" class="sr-only">Height</label>
            <input class="form-control height" id="buid-3" name="video[height]" type="number" />
            <label class="label-pixels">像素</label>
           </div>
          </div>
          <div class="form-group">
           <div class="col-sm-12">
            <label>匹配的视频</label>
            <input class="form-control title" name="video[title]" readonly="" title="标题" type="text" />
            <input class="form-control authorname" name="video[authorname]" readonly="" title="Author" type="text" />
           </div>
           <input class="ratio" name="video[ratio]" type="hidden" />
           <input class="authorurl" name="video[authorurl]" type="hidden" />
           <input class="html" name="video[html]" type="hidden" />
           <input class="thumbnailurl" name="video[thumbnail]" type="hidden" />
          </div>
         </div>
         <div class="col-sm-4">
          <div class="right-on-large">
           <label>预览图片</label>
           <div class="imageholder">
            <img alt="预览图片" data-default-url="<?= $static_url ?>/images/default_empty_4x3.png?0b29c301e1" height="150" src="<?= $static_url ?>/images/default_empty_4x3.png?0b29c301e1" width="200" />
            <button class="hidden" type="button" role="button"></button>
           </div>
          </div>
         </div>
        </fieldset>
       </div>
      </div>
      <div class="tab-pane" id="tab-relations">
       <div data-bolt-fieldset="relationship">
        <fieldset class="form-group bolt-field-relationship" data-bolt-widget="fieldRelationship">
         <legend class="sr-only">Select a page</legend>
         <label class="main col-sm-4 control-label">Select a page: </label>
         <div class="col-sm-8">
          <div class="buic-select" data-bolt-widget="buicSelect">
           <div>
            <div>
             <select id="relation-pages" name="relation[pages][]"><option value="" selected="" label="–"></option><option value="4">Equidem, sed audistine modo de Carneade? (№ 4)</option><option value="5">Negat esse eam, inquit, propter se expetendam. (№ 5)</option><option value="1">Quis est tam dissimile homini. (№ 1)</option><option value="3">Quo modo? (№ 3)</option><option value="2">Sed potestne rerum maior esse dissensio? (№ 2)</option></select>
            </div>
            <div>
             <button type="button" class="btn select-none"><span class="sr-only">取消选择</span></button>
            </div>
           </div>
          </div>
         </div>
        </fieldset>
       </div>
      </div>
      <div class="tab-pane" id="tab-taxonomy">
       <div data-bolt-fieldset="categories">
        <fieldset class="form-group bolt-field-categories" data-bolt-widget="fieldCategories">
         <legend class="sr-only">Categories</legend>
         <label class="main col-sm-3 control-label" for="taxonomy-categories">Categories: </label>
         <div class="col-sm-9">
          <div class="buic-select" data-bolt-widget="buicSelect">
           <div>
            <div>
             <select id="taxonomy-categories" multiple="" name="taxonomy[categories][]"><option value="news">news</option><option value="events">events</option><option value="movies">movies</option><option value="music">music</option><option value="books">books</option><option value="life" selected="">life</option><option value="love">love</option><option value="fun">fun</option></select>
            </div>
            <div>
             <button type="button" class="btn select-none"><span class="sr-only">取消选择</span></button>
             <button type="button" class="btn select-all"><span class="sr-only">全选</span></button>
            </div>
           </div>
          </div>
         </div>
        </fieldset>
       </div>
       <div data-bolt-fieldset="tags">
        <fieldset class="form-group bolt-field-tags" data-bolt-widget="{&quot;fieldTags&quot;:{&quot;slug&quot;:&quot;tags&quot;,&quot;allowSpaces&quot;:false}}" data-tags-url="/async/tags/tags" data-popular-tags-url="/async/populartags/tags">
         <legend class="sr-only">Tags</legend>
         <label class="main col-sm-3 control-label">Tags: </label>
         <div class="col-sm-9">
          <div class="buic-select tags-select2" data-bolt-widget="buicSelect">
           <div>
            <div>
             <select id="taxonomy-tags" multiple="" name="taxonomy[tags][]"><option value="book" selected="">book</option><option value="military" selected="">military</option><option value="world" selected="">world</option><option value="history" selected="">history</option><option value="medicine" selected="">medicine</option></select>
            </div>
            <div>
             <button type="button" class="btn select-none"><span class="sr-only">取消选择</span></button>
            </div>
           </div>
          </div>
          <div class="tagcloud"></div>
         </div>
        </fieldset>
        <div class="postfix">
         <p>Add some freeform tags. Start a new tag by typing a comma or space.</p>
        </div>
       </div>
      </div>
      <div class="tab-pane" id="tab-meta">
       <div data-bolt-fieldset="meta">
        <fieldset class="form-group bolt-field-meta" data-bolt-widget="fieldMeta">
         <legend class="sr-only">元信息</legend>
         <label class="main col-xs-12">元信息: </label>
         <div class="col-xs-12">
          <div class="form-group">
           <label class="col-sm-4 control-label">Id (№)</label>
           <div class="col-sm-8">
            <input class="form-control narrow" name="id" id="id" readonly="readonly" type="text" value="32" />
            <p class="form-control-static"> 已创建此Entries <time class="buic-moment" data-bolt-widget="buicMoment" datetime="2017-07-29T08:25:43+00:00">2017-07-29T08:25:43+00:00</time> 及编辑于 <time class="buic-moment" data-bolt-widget="buicMoment" datetime="2018-02-25T10:49:03+00:00">2018-02-25T10:49:03+00:00</time>. </p>
           </div>
          </div>
          <div class="form-group">
           <label class="col-sm-4 control-label">状态：</label>
           <div class="col-sm-8">
            <select class="form-control" id="statusselect" name="status"> <option value="published" selected="selected">已发布</option> <option value="held">未发布</option> <option value="draft">草稿</option> <option value="timed">定时发布</option> </select>
            <p class="form-control-static"> The status defines whether or not this record is published. Select 'Timed publish' to automatically publish on the set Publication Date. </p>
           </div>
          </div>
          <div class="form-group">
           <label class="col-sm-4 control-label">发布日期：</label>
           <div class="col-sm-8">
            <div class="datetime-container">
             <div>
              <div class="input-group">
               <span class="input-group-btn"> <button type="button" class="btn btn-tertiary"> <i class="fa fa-calendar"></i> </button> </span>
               <input type="text" class="form-control datepicker" />
              </div>
             </div>
             <div>
              <input type="text" class="form-control timepicker" title="Time in 24h/12h format" style="margin-right: 6px;" />
             </div>
             <div>
              <input id="datepublish" name="datepublish" value="2018-02-09 17:51:36" type="hidden" class="datetime" />
              <button type="button" class="btn btn-default btn-xs"> <i class="fa fa-close"></i> </button>
             </div>
            </div>
           </div>
          </div>
          <div class="form-group depublish-group">
           <label class="col-sm-4 control-label">撤除日期：</label>
           <div class="col-sm-8">
            <div class="datetime-container">
             <div>
              <div class="input-group">
               <span class="input-group-btn"> <button type="button" class="btn btn-tertiary"> <i class="fa fa-calendar"></i> </button> </span>
               <input type="text" class="form-control datepicker" />
              </div>
             </div>
             <div>
              <input type="text" class="form-control timepicker" title="Time in 24h/12h format" style="margin-right: 6px;" />
             </div>
             <div>
              <input id="datedepublish" name="datedepublish" value="" type="hidden" class="datetime" data-notice="Depublish date is in the past. Change the status if you want to depublish now." />
              <button type="button" class="btn btn-default btn-xs"> <i class="fa fa-close"></i> </button>
             </div>
            </div>
            <p class="form-control-static"> If set, the record will be automatically depublished on the given date. </p>
           </div>
          </div>
          <div class="form-group">
           <label class="col-sm-4 control-label">所有者：</label>
           <div class="col-sm-8">
            <select class="form-control" id="ownerid" name="ownerid"> <option value="1" selected="selected">管理员</option> </select>
           </div>
          </div>
         </div>
        </fieldset>
       </div>
      </div>
      <input type="hidden" name="_live-editor-preview" value="" />
      <div class="form-group hidden-xs">
       <div class="col-xs-12">
        <div class="btn-group">
         <button type="submit" id="content_edit_save" name="content_edit[save]" class="btn-primary btn"> <i class="fa fa-fw fa-flag"> </i> 保存Entry</button>
         <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button>
         <ul class="dropdown-menu" role="menu">
          <li> <button type="submit" id="content_edit_save_return" name="content_edit[save_return]" class="btn-link btn"> <i class="fa fa-fw fa-flag"> </i> 保存并返回概览</button> </li>
          <li> <button type="submit" id="content_edit_save_create" name="content_edit[save_create]" class="btn-link btn"> <i class="fa fa-fw fa-flag"> </i> 保存并创建新记录</button> </li>
         </ul>
        </div>
        <div class="btn-group">
         <button type="button" id="content_edit_preview" name="content_edit[preview]" class="btn-default btn" data-url="/preview/entry" disabled="disabled"> <i class="fa fa-external-link-square"> </i> 预览</button>
         <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button>
         <ul class="dropdown-menu" role="menu">
          <li> <a href="/entry/quid-enim-est-a-chrysippo-praetermissum-in-stoicis" data-href-placeholder="/entry/__replaceme" target="_blank"> <i class="fa fa-external-link-square"> </i>观看站点中(保存的版本) </a> </li>
         </ul>
        </div>
        <button type="submit" id="content_edit_delete" name="content_edit[delete]" style="visibility: hidden;" class="btn-silent-danger btn"> <i class="fa fa-trash"> </i> 删除Entry</button>
        <p class="lastsaved form-control-static"> 保存在： <strong>日 2/25 10:49:03 2018</strong> <small>(<time class="buic-moment" data-bolt-widget="buicMoment" datetime="2018-02-25T10:49:03+00:00">2018-02-25T10:49:03+00:00</time>)</small> </p>
       </div>
      </div>
     </form>
    </div>
    <aside class="col-md-4 hidden-sm">
     <div class="panel panel-default">
      <div class="panel-heading">
       <i class="fa fa-cog fa-fw"></i> 对Entry的操作
      </div>
      <div class="panel-body">
       <div class="btn-group">
        <button type="button" class="btn btn-primary" id="sidebar_save"> <i class="fa fa-flag"></i> 保存Entry </button>
        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button>
        <ul class="dropdown-menu" role="menu">
         <li> <button type="submit" class="btn btn-link" id="sidebar_save_return"> <i class="fa fa-fw fa-flag"></i> 保存并返回概览 </button> </li>
         <li> <button type="submit" class="btn btn-link" id="sidebar_save_create"> <i class="fa fa-fw fa-flag"></i> 保存并创建新记录 </button> </li>
        </ul>
       </div>
       <div class="btn-group">
        <button type="button" class="btn btn-default" id="sidebar_preview" data-url="/preview/entry"> <i class="fa fa-external-link-square"></i> 预览 </button>
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button>
        <ul class="dropdown-menu pull-right" role="menu">
         <li> <a href="/entry/quid-enim-est-a-chrysippo-praetermissum-in-stoicis" data-href-placeholder="/entry/__replaceme" target="_blank"> <i class="fa fa-external-link-square"></i> 观看站点中(保存的版本) </a> </li>
        </ul>
       </div>
       <p> 当前(保存的)状态: <a href="#statusselect" id="lastsavedstatus" title="点击改变当前状态。"> <strong> <i class="fa fa-circle status-published"></i> 已发布 </strong> </a> </p>
       <p class="lastsaved"> 保存在： <strong>日 2/25 10:49:03 2018</strong> <small>(<time class="buic-moment" data-bolt-widget="buicMoment" datetime="2018-02-25T10:49:03+00:00">2018-02-25T10:49:03+00:00</time>)</small> </p>
       <div class="btn-group">
        <button type="button" class="btn btn-silent-danger" id="sidebar_delete"> <i class="fa fa-trash"></i> 删除entry </button>
       </div>
      </div>
     </div>
     <div class="panel panel-default panel-lastmodified">
      <div class="panel-heading">
       <i class="fa fa-fw fa-clock-o"></i> 最近修改的Entries
      </div>
      <div class="panel-body">
       <ul>
        <li> № 35. <a href="/bolt/editcontent/entries/35">ALIO MODO.</a> <small>(<time class="buic-moment" data-bolt-widget="buicMoment" datetime="2018-02-25T10:49:13+00:00">2018-02-25T10:49:13+00:00</time>)</small> </li>
        <li> № 34. <a href="/bolt/editcontent/entries/34">Nescio quo modo praetervolavit oratio.</a> <small>(<time class="buic-moment" data-bolt-widget="buicMoment" datetime="2018-02-25T10:49:10+00:00">2018-02-25T10:49:10+00:00</time>)</small> </li>
        <li> № 33. <a href="/bolt/editcontent/entries/33">Sin aliud quid voles, postea.</a> <small>(<time class="buic-moment" data-bolt-widget="buicMoment" datetime="2018-02-25T10:49:08+00:00">2018-02-25T10:49:08+00:00</time>)</small> </li>
        <li> № 32. <a href="/bolt/editcontent/entries/32">Quid enim est a Chrysippo praetermissum in Stoicis?</a> <small>(<time class="buic-moment" data-bolt-widget="buicMoment" datetime="2018-02-25T10:49:03+00:00">2018-02-25T10:49:03+00:00</time>)</small> </li>
        <li> № 31. <a href="/bolt/editcontent/entries/31">Immo videri fortasse.</a> <small>(<time class="buic-moment" data-bolt-widget="buicMoment" datetime="2018-02-25T10:49:00+00:00">2018-02-25T10:49:00+00:00</time>)</small> </li>
       </ul>
      </div>
     </div>
     <div class="panel panel-default panel-stack">
      <div class="panel-heading">
       <i class="fa fa-fw fa-paperclip"></i> 暂存区中的文件
      </div>
      <div class="panel-body">
       <fieldset class="buic-stack" data-bolt-widget="buicStack">
        <div class="stackholder">
         <div class="empty">
           暂存区中没有东西。
         </div>
        </div>
        <div class="buic-progress" data-bolt-widget="buicProgress"></div>
        <div class="stackbuttons">
         <div class="btn-group">
          <span class="btn btn-tertiary fileinput-button"> <i class="fa fa-upload"></i> <span>上传</span> <input id="fileupload-stack" type="file" name="files[]" data-url="/bolt/upload" accept=".twig,.html,.js,.css,.scss,.gif,.jpg,.jpeg,.png,.ico,.zip,.tgz,.txt,.md,.doc,.docx,.pdf,.epub,.xls,.xlsx,.ppt,.pptx,.mp3,.ogg,.wav,.m4a,.mp4,.m4v,.ogv,.wmv,.avi,.webm,.svg" /> </span>
         </div>
         <div class="btn-group">
          <button class="btn btn-tertiary" data-bolt-widget="{&quot;buicBrowser&quot;:{&quot;url&quot;:&quot;/async/browse&quot;}}" type="button"><i class="fa fa-plus"></i> 选择</button>
         </div>
        </div>
       </fieldset>
      </div>
     </div>
    </aside>
   </div>
  </div>
<?php $this->blockEnd(); ?>

<?php $this->blockStart('scripts'); ?>
<script src="/bolt-public/js/ckeditor/ckeditor.js?5148931881"></script>
<script src="/bolt-public/js/bolt.js?1721f6ee98" data-config="{&quot;locale&quot;:{&quot;short&quot;:&quot;zh&quot;,&quot;long&quot;:&quot;zh_CN&quot;},&quot;timezone&quot;:{&quot;offset&quot;:&quot;+00:00&quot;},&quot;buid&quot;:&quot;buid-4&quot;,&quot;uploadConfig&quot;:{&quot;url&quot;:&quot;\/bolt\/files&quot;,&quot;acceptFilesTypes&quot;:[&quot;twig&quot;,&quot;html&quot;,&quot;js&quot;,&quot;css&quot;,&quot;scss&quot;,&quot;gif&quot;,&quot;jpg&quot;,&quot;jpeg&quot;,&quot;png&quot;,&quot;ico&quot;,&quot;zip&quot;,&quot;tgz&quot;,&quot;txt&quot;,&quot;md&quot;,&quot;doc&quot;,&quot;docx&quot;,&quot;pdf&quot;,&quot;epub&quot;,&quot;xls&quot;,&quot;xlsx&quot;,&quot;ppt&quot;,&quot;pptx&quot;,&quot;mp3&quot;,&quot;ogg&quot;,&quot;wav&quot;,&quot;m4a&quot;,&quot;mp4&quot;,&quot;m4v&quot;,&quot;ogv&quot;,&quot;wmv&quot;,&quot;avi&quot;,&quot;webm&quot;,&quot;svg&quot;],&quot;maxSize&quot;:8346664.96,&quot;maxSizeNice&quot;:&quot;7.96 MiB&quot;},&quot;ckeditor&quot;:{&quot;lang&quot;:&quot;zh&quot;,&quot;images&quot;:0,&quot;styles&quot;:0,&quot;tables&quot;:0,&quot;anchor&quot;:0,&quot;fontcolor&quot;:0,&quot;align&quot;:0,&quot;subsuper&quot;:0,&quot;embed&quot;:0,&quot;blockquote&quot;:0,&quot;ruler&quot;:0,&quot;strike&quot;:0,&quot;underline&quot;:0,&quot;codesnippet&quot;:0,&quot;specialchar&quot;:0,&quot;ck&quot;:{&quot;autoParagraph&quot;:true,&quot;contentsCss&quot;:[&quot;\/bolt-public\/css\/ckeditor-contents.css?d9e9ff3e46&quot;,&quot;\/bolt-public\/css\/ckeditor.css?f47046d0e4&quot;],&quot;filebrowserWindowWidth&quot;:640,&quot;filebrowserWindowHeight&quot;:480,&quot;disableNativeSpellChecker&quot;:true,&quot;allowNbsp&quot;:false},&quot;filebrowser&quot;:{&quot;browseUrl&quot;:&quot;\/async\/recordbrowser&quot;,&quot;imageBrowseUrl&quot;:&quot;\/bolt\/files&quot;}},&quot;stackAddUrl&quot;:&quot;\/async\/stack\/add&quot;,&quot;contentActionUrl&quot;:&quot;\/async\/content\/action&quot;,&quot;google_api_key&quot;:null}" data-jsdata="{&quot;omnisearch&quot;:{&quot;placeholder&quot;:&quot;\u67e5\u627e&quot;},&quot;validation&quot;:{&quot;alertbox&quot;:&quot;&lt;div id=\&quot;%NOTICE_ID%\&quot; class=\&quot;alert alert-danger alert-dismissible\&quot;&gt;\n        &lt;button type=\&quot;button\&quot; class=\&quot;close\&quot; data-dismiss=\&quot;alert\&quot;&gt;\u00d7&lt;\/button&gt;\n        &lt;label for=\&quot;%FIELD_ID%\&quot;&gt;Field \u201c%FIELD_NAME%\u201d:&lt;\/label&gt;\n        %MESSAGE%\n    &lt;\/div&gt;&quot;,&quot;generic_msg&quot;:&quot;Is required or needs to match a pattern!&quot;},&quot;editcontent&quot;:{&quot;msg&quot;:{&quot;change_quit&quot;:&quot;You have unfinished changes on this page. If you continue without saving, you will lose these changes.&quot;,&quot;saving&quot;:&quot;\u6b63\u5728\u4fdd\u5b58 \u2026&quot;},&quot;delete&quot;:&quot;Are you sure you wish to delete this record? There is no undo.&quot;},&quot;endpoint&quot;:{&quot;embed&quot;:&quot;\/async\/embed&quot;},&quot;field&quot;:{&quot;relationship&quot;:{&quot;text&quot;:{&quot;placeholder&quot;:&quot;(\u65e0)&quot;}},&quot;categories&quot;:{&quot;text&quot;:{&quot;placeholder&quot;:&quot;(\u6ca1\u6709\u5206\u7c7b)&quot;}},&quot;uploads&quot;:{&quot;template&quot;:{&quot;error&quot;:&quot;&lt;p&gt;There was an error uploading the file. Make sure the file is not corrupt,\n    and that the upload-folder is writable.&lt;\/p&gt;&lt;p&gt;Error message:&lt;br&gt;&lt;i&gt;%ERROR%&lt;i&gt;&lt;\/p&gt;&quot;,&quot;large-file&quot;:&quot;&lt;p&gt;File is too large:&lt;\/p&gt;&lt;table&gt;&lt;tr&gt;&lt;th&gt;Name: &lt;\/th&gt;&lt;td&gt;%FILENAME%&lt;\/td&gt;&lt;\/tr&gt;&lt;tr&gt;&lt;th&gt;Size: &lt;\/th&gt;&lt;td&gt;%FILESIZE%&lt;\/td&gt;&lt;\/tr&gt;&lt;tr&gt;&lt;th&gt;Maximum size: &lt;\/th&gt;&lt;td&gt;%ALLOWED%&lt;\/td&gt;&lt;\/tr&gt;&lt;\/table&gt;&quot;,&quot;wrong-type&quot;:&quot;&lt;p&gt;File type not allowed:&lt;\/p&gt;&lt;table&gt;&lt;tr&gt;&lt;th&gt;Name:&lt;\/th&gt;&lt;td&gt;%FILENAME%&lt;\/td&gt;&lt;\/tr&gt;&lt;tr&gt;&lt;th&gt;Type:&lt;\/th&gt;&lt;td&gt;%FILETYPE%&lt;\/td&gt;&lt;\/tr&gt;&lt;tr&gt;&lt;th&gt;Allowed types:&lt;\/th&gt;&lt;td&gt;%ALLOWED%&lt;\/td&gt;&lt;\/tr&gt;&lt;\/table&gt;&quot;}}}}"></script>
  <div id="sfwdt8a9bce" class="sf-toolbar" style="display: none"></div>
  <script>/*<![CDATA[*/        Sfjs = (function() {        "use strict";        var classListIsSupported = 'classList' in document.documentElement;        if (classListIsSupported) {            var hasClass = function (el, cssClass) { return el.classList.contains(cssClass); };            var removeClass = function(el, cssClass) { el.classList.remove(cssClass); };            var addClass = function(el, cssClass) { el.classList.add(cssClass); };            var toggleClass = function(el, cssClass) { el.classList.toggle(cssClass); };        } else {            var hasClass = function (el, cssClass) { return el.className.match(new RegExp('\\b' + cssClass + '\\b')); };            var removeClass = function(el, cssClass) { el.className = el.className.replace(new RegExp('\\b' + cssClass + '\\b'), ' '); };            var addClass = function(el, cssClass) { if (!hasClass(el, cssClass)) { el.className += " " + cssClass; } };            var toggleClass = function(el, cssClass) { hasClass(el, cssClass) ? removeClass(el, cssClass) : addClass(el, cssClass); };        }        var noop = function() {},            collectionToArray = function (collection) {                var length = collection.length || 0,                    results = new Array(length);                while (length--) {                    results[length] = collection[length];                }                return results;            },            profilerStorageKey = 'sf2/profiler/',            request = function(url, onSuccess, onError, payload, options) {                var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');                options = options || {};                options.maxTries = options.maxTries || 0;                xhr.open(options.method || 'GET', url, true);                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');                xhr.onreadystatechange = function(state) {                    if (4 !== xhr.readyState) {                        return null;                    }                    if (xhr.status == 404 && options.maxTries > 1) {                        setTimeout(function(){                            options.maxTries--;                            request(url, onSuccess, onError, payload, options);                        }, 500);                        return null;                    }                    if (200 === xhr.status) {                        (onSuccess || noop)(xhr);                    } else {                        (onError || noop)(xhr);                    }                };                xhr.send(payload || '');            },            getPreference = function(name) {                if (!window.localStorage) {                    return null;                }                return localStorage.getItem(profilerStorageKey + name);            },            setPreference = function(name, value) {                if (!window.localStorage) {                    return null;                }                localStorage.setItem(profilerStorageKey + name, value);            },            requestStack = [],            extractHeaders = function(xhr, stackElement) {                /* Here we avoid to call xhr.getResponseHeader in order to */                /* prevent polluting the console with CORS security errors */                var allHeaders = xhr.getAllResponseHeaders();                var ret;                if (ret = allHeaders.match(/^x-debug-token:\s+(.*)$/im)) {                    stackElement.profile = ret[1];                }                if (ret = allHeaders.match(/^x-debug-token-link:\s+(.*)$/im)) {                    stackElement.profilerUrl = ret[1];                }            },            renderAjaxRequests = function() {                var requestCounter = document.querySelectorAll('.sf-toolbar-ajax-requests');                if (!requestCounter.length) {                    return;                }                var ajaxToolbarPanel = document.querySelector('.sf-toolbar-block-ajax');                var tbodies = document.querySelectorAll('.sf-toolbar-ajax-request-list');                var state = 'ok';                if (tbodies.length) {                    var tbody = tbodies[0];                    var rows = document.createDocumentFragment();                    if (requestStack.length) {                        for (var i = 0; i < requestStack.length; i++) {                            var request = requestStack[i];                            var row = document.createElement('tr');                            rows.insertBefore(row, rows.firstChild);                            var methodCell = document.createElement('td');                            if (request.error) {                                methodCell.className = 'sf-ajax-request-error';                            }                            methodCell.textContent = request.method;                            row.appendChild(methodCell);                            var pathCell = document.createElement('td');                            pathCell.className = 'sf-ajax-request-url';                            if ('GET' === request.method) {                                var pathLink = document.createElement('a');                                pathLink.setAttribute('href', request.url);                                pathLink.textContent = request.url;                                pathCell.appendChild(pathLink);                            } else {                                pathCell.textContent = request.url;                            }                            pathCell.setAttribute('title', request.url);                            row.appendChild(pathCell);                            var durationCell = document.createElement('td');                            durationCell.className = 'sf-ajax-request-duration';                            if (request.duration) {                                durationCell.textContent = request.duration + "ms";                            } else {                                durationCell.textContent = '-';                            }                            row.appendChild(durationCell);                            row.appendChild(document.createTextNode(' '));                            var profilerCell = document.createElement('td');                            if (request.profilerUrl) {                                var profilerLink = document.createElement('a');                                profilerLink.setAttribute('href', request.profilerUrl);                                profilerLink.textContent = request.profile;                                profilerCell.appendChild(profilerLink);                            } else {                                profilerCell.textContent = 'n/a';                            }                            row.appendChild(profilerCell);                            var requestState = 'ok';                            if (request.error) {                                requestState = 'error';                                if (state != "loading" && i > requestStack.length - 4) {                                    state = 'error';                                }                            } else if (request.loading) {                                requestState = 'loading';                                state = 'loading';                            }                            row.className = 'sf-ajax-request sf-ajax-request-' + requestState;                        }                        var infoSpan = document.querySelectorAll(".sf-toolbar-ajax-info")[0];                        var children = collectionToArray(tbody.children);                        for (var i = 0; i < children.length; i++) {                            tbody.removeChild(children[i]);                        }                        tbody.appendChild(rows);                        if (infoSpan) {                            var text = requestStack.length + ' AJAX request' + (requestStack.length > 1 ? 's' : '');                            infoSpan.textContent = text;                        }                        ajaxToolbarPanel.style.display = 'block';                    } else {                        ajaxToolbarPanel.style.display = 'none';                    }                }                requestCounter[0].textContent = requestStack.length;                var className = 'sf-toolbar-ajax-requests sf-toolbar-value';                requestCounter[0].className = className;                if (state == 'ok') {                    Sfjs.removeClass(ajaxToolbarPanel, 'sf-ajax-request-loading');                    Sfjs.removeClass(ajaxToolbarPanel, 'sf-toolbar-status-red');                } else if (state == 'error') {                    Sfjs.addClass(ajaxToolbarPanel, 'sf-toolbar-status-red');                    Sfjs.removeClass(ajaxToolbarPanel, 'sf-ajax-request-loading');                } else {                    Sfjs.addClass(ajaxToolbarPanel, 'sf-ajax-request-loading');                }            };        var addEventListener;        var el = document.createElement('div');        if (!('addEventListener' in el)) {            addEventListener = function (element, eventName, callback) {                element.attachEvent('on' + eventName, callback);            };        } else {            addEventListener = function (element, eventName, callback) {                element.addEventListener(eventName, callback, false);            };        }                    if (window.XMLHttpRequest && XMLHttpRequest.prototype.addEventListener) {                var proxied = XMLHttpRequest.prototype.open;                XMLHttpRequest.prototype.open = function(method, url, async, user, pass) {                    var self = this;                    /* prevent logging AJAX calls to static and inline files, like templates */                    var path = url;                    if (url.substr(0, 1) === '/') {                        if (0 === url.indexOf('')) {                            path = url.substr(0);                        }                    }                    else if (0 === url.indexOf('http\x3A\x2F\x2Fbolt.d.tongtong.me\x3A8080')) {                        path = url.substr(30);                    }                    if (!path.match(new RegExp("^\/bundles|^\/_wdt"))) {                        var stackElement = {                            loading: true,                            error: false,                            url: url,                            method: method,                            start: new Date()                        };                        requestStack.push(stackElement);                        this.addEventListener('readystatechange', function() {                            if (self.readyState == 4) {                                stackElement.duration = new Date() - stackElement.start;                                stackElement.loading = false;                                stackElement.error = self.status < 200 || self.status >= 400;                                extractHeaders(self, stackElement);                                Sfjs.renderAjaxRequests();                            }                        }, false);                        Sfjs.renderAjaxRequests();                    }                    proxied.apply(this, Array.prototype.slice.call(arguments));                };            }                return {            hasClass: hasClass,            removeClass: removeClass,            addClass: addClass,            toggleClass: toggleClass,            getPreference: getPreference,            setPreference: setPreference,            addEventListener: addEventListener,            request: request,            renderAjaxRequests: renderAjaxRequests,            load: function(selector, url, onSuccess, onError, options) {                var el = document.getElementById(selector);                if (el && el.getAttribute('data-sfurl') !== url) {                    request(                        url,                        function(xhr) {                            el.innerHTML = xhr.responseText;                            el.setAttribute('data-sfurl', url);                            removeClass(el, 'loading');                            (onSuccess || noop)(xhr, el);                        },                        function(xhr) { (onError || noop)(xhr, el); },                        '',                        options                    );                }                return this;            },            toggle: function(selector, elOn, elOff) {                var tmp = elOn.style.display,                    el = document.getElementById(selector);                elOn.style.display = elOff.style.display;                elOff.style.display = tmp;                if (el) {                    el.style.display = 'none' === tmp ? 'none' : 'block';                }                return this;            },            createTabs: function() {                var tabGroups = document.querySelectorAll('.sf-tabs');                /* create the tab navigation for each group of tabs */                for (var i = 0; i < tabGroups.length; i++) {                    var tabs = tabGroups[i].querySelectorAll('.tab');                    var tabNavigation = document.createElement('ul');                    tabNavigation.className = 'tab-navigation';                    for (var j = 0; j < tabs.length; j++) {                        var tabId = 'tab-' + i + '-' + j;                        var tabTitle = tabs[j].querySelector('.tab-title').innerHTML;                        var tabNavigationItem = document.createElement('li');                        tabNavigationItem.setAttribute('data-tab-id', tabId);                        if (j == 0) { Sfjs.addClass(tabNavigationItem, 'active'); }                        if (Sfjs.hasClass(tabs[j], 'disabled')) { Sfjs.addClass(tabNavigationItem, 'disabled'); }                        tabNavigationItem.innerHTML = tabTitle;                        tabNavigation.appendChild(tabNavigationItem);                        var tabContent = tabs[j].querySelector('.tab-content');                        tabContent.parentElement.setAttribute('id', tabId);                    }                    tabGroups[i].insertBefore(tabNavigation, tabGroups[i].firstChild);                }                /* display the active tab and add the 'click' event listeners */                for (i = 0; i < tabGroups.length; i++) {                    tabNavigation = tabGroups[i].querySelectorAll('.tab-navigation li');                    for (j = 0; j < tabNavigation.length; j++) {                        tabId = tabNavigation[j].getAttribute('data-tab-id');                        document.getElementById(tabId).querySelector('.tab-title').className = 'hidden';                        if (Sfjs.hasClass(tabNavigation[j], 'active')) {                            document.getElementById(tabId).className = 'block';                        } else {                            document.getElementById(tabId).className = 'hidden';                        }                        tabNavigation[j].addEventListener('click', function(e) {                            var activeTab = e.target || e.srcElement;                            /* needed because when the tab contains HTML contents, user can click */                            /* on any of those elements instead of their parent '<li>' element */                            while (activeTab.tagName.toLowerCase() !== 'li') {                                activeTab = activeTab.parentNode;                            }                            /* get the full list of tabs through the parent of the active tab element */                            var tabNavigation = activeTab.parentNode.children;                            for (var k = 0; k < tabNavigation.length; k++) {                                var tabId = tabNavigation[k].getAttribute('data-tab-id');                                document.getElementById(tabId).className = 'hidden';                                Sfjs.removeClass(tabNavigation[k], 'active');                            }                            Sfjs.addClass(activeTab, 'active');                            var activeTabId = activeTab.getAttribute('data-tab-id');                            document.getElementById(activeTabId).className = 'block';                        });                    }                }            },            createToggles: function() {                var toggles = document.querySelectorAll('.sf-toggle');                for (var i = 0; i < toggles.length; i++) {                    var elementSelector = toggles[i].getAttribute('data-toggle-selector');                    var element = document.querySelector(elementSelector);                    Sfjs.addClass(element, 'sf-toggle-content');                    if (toggles[i].hasAttribute('data-toggle-initial') && toggles[i].getAttribute('data-toggle-initial') == 'display') {                        Sfjs.addClass(element, 'sf-toggle-visible');                    } else {                        Sfjs.addClass(element, 'sf-toggle-hidden');                    }                    Sfjs.addEventListener(toggles[i], 'click', function(e) {                        e.preventDefault();                        var toggle = e.target || e.srcElement;                        /* needed because when the toggle contains HTML contents, user can click */                        /* on any of those elements instead of their parent '.sf-toggle' element */                        while (!Sfjs.hasClass(toggle, 'sf-toggle')) {                            toggle = toggle.parentNode;                        }                        var element = document.querySelector(toggle.getAttribute('data-toggle-selector'));                        Sfjs.toggleClass(element, 'sf-toggle-hidden');                        Sfjs.toggleClass(element, 'sf-toggle-visible');                        /* the toggle doesn't change its contents when clicking on it */                        if (!toggle.hasAttribute('data-toggle-alt-content')) {                            return;                        }                        if (!toggle.hasAttribute('data-toggle-original-content')) {                            toggle.setAttribute('data-toggle-original-content', toggle.innerHTML);                        }                        var currentContent = toggle.innerHTML;                        var originalContent = toggle.getAttribute('data-toggle-original-content');                        var altContent = toggle.getAttribute('data-toggle-alt-content');                        toggle.innerHTML = currentContent !== altContent ? altContent : originalContent;                    });                }            }        };    })();    Sfjs.addEventListener(window, 'load', function() {        Sfjs.createTabs();        Sfjs.createToggles();    });/*]]>*/</script>
  <script>/*<![CDATA[*/    (function () {                Sfjs.load(            'sfwdt8a9bce',            '/_profiler/wdt/8a9bce',            function(xhr, el) {                el.style.display = -1 !== xhr.responseText.indexOf('sf-toolbarreset') ? 'block' : 'none';                if (el.style.display == 'none') {                    return;                }                if (Sfjs.getPreference('toolbar/displayState') == 'none') {                    document.getElementById('sfToolbarMainContent-8a9bce').style.display = 'none';                    document.getElementById('sfToolbarClearer-8a9bce').style.display = 'none';                    document.getElementById('sfMiniToolbar-8a9bce').style.display = 'block';                } else {                    document.getElementById('sfToolbarMainContent-8a9bce').style.display = 'block';                    document.getElementById('sfToolbarClearer-8a9bce').style.display = 'block';                    document.getElementById('sfMiniToolbar-8a9bce').style.display = 'none';                }                Sfjs.renderAjaxRequests();                /* Handle toolbar-info position */                var toolbarBlocks = document.querySelectorAll('.sf-toolbar-block');                for (var i = 0; i < toolbarBlocks.length; i += 1) {                    toolbarBlocks[i].onmouseover = function () {                        var toolbarInfo = this.querySelectorAll('.sf-toolbar-info')[0];                        var pageWidth = document.body.clientWidth;                        var elementWidth = toolbarInfo.offsetWidth;                        var leftValue = (elementWidth + this.offsetLeft) - pageWidth;                        var rightValue = (elementWidth + (pageWidth - this.offsetLeft)) - pageWidth;                        /* Reset right and left value, useful on window resize */                        toolbarInfo.style.right = '';                        toolbarInfo.style.left = '';                        if (elementWidth > pageWidth) {                            toolbarInfo.style.left = 0;                        }                        else if (leftValue > 0 && rightValue > 0) {                            toolbarInfo.style.right = (rightValue * -1) + 'px';                        } else if (leftValue < 0) {                            toolbarInfo.style.left = 0;                        } else {                            toolbarInfo.style.right = '0px';                        }                    };                }                var dumpInfo = document.querySelector('.sf-toolbar-block-dump .sf-toolbar-info');                if (null !== dumpInfo) {                    Sfjs.addEventListener(dumpInfo, 'sfbeforedumpcollapse', function () {                        dumpInfo.style.minHeight = dumpInfo.getBoundingClientRect().height+'px';                    });                    Sfjs.addEventListener(dumpInfo, 'mouseleave', function () {                        dumpInfo.style.minHeight = '';                    });                }            },            function(xhr) {                if (xhr.status !== 0) {                    var sfwdt = document.getElementById('sfwdt8a9bce');                    sfwdt.innerHTML = '\                        <div class="sf-toolbarreset">\                            <div class="sf-toolbar-icon"><svg width="26" height="28" xmlns="http://www.w3.org/2000/svg" version="1.1" x="0px" y="0px" viewBox="0 0 26 28" enable-background="new 0 0 26 28" xml:space="preserve"><path fill="#FFFFFF" d="M13 0C5.8 0 0 5.8 0 13c0 7.2 5.8 13 13 13c7.2 0 13-5.8 13-13C26 5.8 20.2 0 13 0z M20 7.5 c-0.6 0-1-0.3-1-0.9c0-0.2 0-0.4 0.2-0.6c0.1-0.3 0.2-0.3 0.2-0.4c0-0.3-0.5-0.4-0.7-0.4c-2 0.1-2.5 2.7-2.9 4.8l-0.2 1.1 c1.1 0.2 1.9 0 2.4-0.3c0.6-0.4-0.2-0.8-0.1-1.3C18 9.2 18.4 9 18.7 8.9c0.5 0 0.8 0.5 0.8 1c0 0.8-1.1 2-3.3 1.9 c-0.3 0-0.5 0-0.7-0.1L15 14.1c-0.4 1.7-0.9 4.1-2.6 6.2c-1.5 1.8-3.1 2.1-3.8 2.1c-1.3 0-2.1-0.6-2.2-1.6c0-0.9 0.8-1.4 1.3-1.4 c0.7 0 1.2 0.5 1.2 1.1c0 0.5-0.2 0.6-0.4 0.7c-0.1 0.1-0.3 0.2-0.3 0.4c0 0.1 0.1 0.3 0.4 0.3c0.5 0 0.9-0.3 1.2-0.5 c1.3-1 1.7-2.9 2.4-6.2l0.1-0.8c0.2-1.1 0.5-2.3 0.8-3.5c-0.9-0.7-1.4-1.5-2.6-1.8c-0.8-0.2-1.3 0-1.7 0.4C8.4 10 8.6 10.7 9 11.1 l0.7 0.7c0.8 0.9 1.3 1.7 1.1 2.7c-0.3 1.6-2.1 2.8-4.3 2.1c-1.9-0.6-2.2-1.9-2-2.7c0.2-0.6 0.7-0.8 1.2-0.6 c0.5 0.2 0.7 0.8 0.6 1.3c0 0.1 0 0.1-0.1 0.3C6 15 5.9 15.2 5.9 15.3c-0.1 0.4 0.4 0.7 0.8 0.8c0.8 0.3 1.7-0.2 1.9-0.9 c0.2-0.6-0.2-1.1-0.4-1.2l-0.8-0.9c-0.4-0.4-1.2-1.5-0.8-2.8c0.2-0.5 0.5-1 0.9-1.4c1-0.7 2-0.8 3-0.6c1.3 0.4 1.9 1.2 2.8 1.9 c0.5-1.3 1.1-2.6 2-3.8c0.9-1 2-1.7 3.3-1.8C20 4.8 21 5.4 21 6.3C21 6.7 20.8 7.5 20 7.5z"/></svg></div>\                            An error occurred while loading the web debug toolbar. <a href="/_profiler/8a9bce">Open the web profiler.</a>\                        </div>\                    ';                    sfwdt.setAttribute('class', 'sf-toolbar sf-error-toolbar');                }            },            {'maxTries': 5}        );    })();/*]]>*/</script>
<?php $this->blockEnd(); ?>
