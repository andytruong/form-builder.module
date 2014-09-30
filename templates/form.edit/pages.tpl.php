<div class="item-list">
  <ul>
    <li class="draggable page"
        ng-repeat="page in pages|orderBy:'weight'"
        ng-init="pageId = page.uuid">

      <div class="drag-icon"
           ui-draggable="true" drag="pageId" ui-draggable="true"
           ui-on-Drop="pageOnDrop($event, $data, pageId)">
        <span>{{entity.layoutOptions.pages[pageId].title}}</span>
      </div>

      <!-- General page information -->
      <?php include 'pages/info.tpl.php'; ?>

      <!-- Fields & Groups -->
      <div class="page-stack"></div>

      <!-- Extra informations -->
      <?php include 'pages/actions.tpl.php'; ?>
    </li>

    <!-- If there's no page -->
    <li ng-if="!pages.length">No page available</li>
  </ul>
</div>
