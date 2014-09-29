<div class="item-list">
  <ul>
    <li class="draggable page"
        ng-repeat="page in pages|orderBy:'weight'"
        ng-init="pageId = page.uuid">

      <div class="drag-icon"
           ui-draggable="true"
           drag="pageId"
           ui-draggable="true"
           ui-on-Drop="pageOnDrop($event, $data, pageId)">
        <span>{{entity.layoutOptions.pages[pageId].title}}</span>
      </div>

      <?php include 'pages/info.tpl.php'; ?>
      <?php include 'pages/stack.tpl.php'; ?>
      <?php include 'pages/actions.tpl.php'; ?>
    </li>
    <li ng-if="!pages.length">No page available</li>
  </ul>
</div>
