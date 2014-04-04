<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title><?= $d['pageHeadTitle'] ?></title>

  <? if ($d['page']['home'] and ($v = Config::getVarVar('yandex', 'verification', true))) { ?>
  <meta name="yandex-verification" content="<?= $v ?>"/>
  <? } ?>

  <base href="<?= (new SiteRequest)->getAbsBase() ?>/" />

  <? if (!empty($d['pageMeta']['description'])) { ?>
  <meta name="description" content="<?= $d['pageMeta']['description'] ?>"/>
  <? } ?>
  <? if (!empty($d['pageMeta']['keywords'])) { ?>
  <meta name="keywords" content="<?= $d['pageMeta']['keywords'] ?>"/>
  <? } ?>

  <?= Sflm::frontend('css')->getTags(SITE_SET) // Site Set CSS ?>
  <script type="text/javascript" src="/i/js/tiny_mce/tiny_mce.js"></script>
  <?= Sflm::frontend('js')->getTags(SITE_SET) // Site Set JS ?>
  <?= StmCore::getTags() // Site Theme CSS & JS ?>
  <?
  // Site Module CSS & JS
  if (!empty($d['page']['module'])) {
    print FileCache::func(function() use ($d) {
      return PageModuleCore::sf('site', $d['page']['module']);
    }, $d['page']['module'].'sf', FORCE_STATIC_FILES_CACHE);
  }
  ?>
  <script type="text/javascript">
    <? $this->tpl('js', $d, true) // Dynamic JS ?>
  </script>
  <? if ($d['user']) $this->tpl('common/userThemeCss', $d['user']) ?>

  <?/*
  <script>
    window.addEvent('domready', function() {
      $('menu').addClass('dragging');
      new Drag.Move($('menu'), {
        droppables: '.menuCont',
        onStart: function(el) {
        },
        onDrop: function(el, droppable, event) {
        },
        onEnter: function(el, droppable) {
          droppable.addClass('hl');
        },
        onLeave: function(el, droppable) {
          droppable.removeClass('hl');
        }
      });

      var btn = new Ngn.Btn(Ngn.btn1('Изменить фон', 'edit').inject($('layout')), null, {
        fileUpload: {
          url: '/siteDraw/ajax_upload',
          onRequest: function() {
          },
          onComplete: function() {
            $('layout').setStyle('background-image', 'url(/m/sd/bg?'+Ngn.randString(5)+')');
          }
        }
      });

      var initPos, pos, newPos;

      new Drag(new Element('a', { 'class' : 'dragBox' }).inject($('layout')), {
        onStart: function(el, e) {
          initPos = [e.event.clientX, e.event.clientY];
        },
        onDrag: function(el, e) {
          pos = [e.event.clientX, e.event.clientY];
          newPos = [initPos[0] - pos[0], initPos[1] - pos[1]];
          $('layout').setStyle('background-position', (-newPos[0])+'px '+(-newPos[1])+'px');
        },
        onComplete: function(el) {
          //initPos = newPos;
        }
      });

      var btn = new Ngn.Btn(Ngn.btn1('Добавить слайс', 'add').inject($('layout')), function() {
        new Ngn.Request.JSON({
          url: '/c/slice/json_create',
          onComplete: function() {
            window.reload(true);
          }
        }).send({
          title: 'asd',
          id: 'sample',
          text: 'sample'
        });
      });

      Ngn.eSlider = function() {
        return Elements.from('<div class="slider"><div class="knob"></div></div>')[0];
      };
      document.getElements('.sliceType_text').each(function(el) {
        var eSlider = Ngn.eSlider().inject(el, 'before');
        var eText = el.getElement('.slice-text');
        Cufon.replace(eText);
        new Slider(eSlider, eSlider.getElement('.knob'), {
          range: [9, 100],
          initialStep: 14,
          onChange: function(value){
            eText.setStyle('font-size', value+'px');
            Cufon.refresh(eText);
          }
        });


        var btn = Ngn.btn1('Сменить цвет', 'edit').inject($('layout'));
        new Ngn.Btn(btn);
        new MooRainbow(btn, {
          imgPath: '/i/img/rainbow/small/',
          wheel: true,
          startColor: [255, 255, 255],
          onChange: function(color) {
            eText.setStyle('color', color.hex);
            Cufon.refresh(eText);
          },
          onComplete: function(color) {
          }
        });

      });



    });



  </script>

  <style>

    .slider {
      background: #CCC;
      height: 16px;
      width: 200px;
    }
    .slider .knob {
      background: #000;
      width: 16px;
      height: 16px;
    }

    .menuCont {
      height: 100px;
    }
    .dragging {
      border: 1px solid #f00;
      position: absolute;
    }
    .hl {
      border: 1px solid #8DB82C;
    }
    #layout {
      background-repeat: no-repeat;
      background-image: url(/m/sd/bg);
      background-position: 0px 0px;
    }
  </style>
*/?>

</head>
