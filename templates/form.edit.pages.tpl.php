<div class="item-list">
    <ul>
        <li class="draggable page"
            ng-repeat="page in pages|orderBy:'weight'"
            ng-init="pageUuid = page.uuid">

            <div class="drag-icon"
                 ui-draggable="true"
                 drag="pageUuid"
                 ui-draggable="true"
                 ui-on-Drop="pageOnDrop($event, $data, pageUuid)">
                <span>{{entity.layoutOptions.pages[pageUuid].title}}</span>
            </div>

            <?php include 'form.edit.pages.info.tpl.php'; ?>
            <ul>
                <?php include 'form.edit.fields.item.tpl.php'; ?>
            </ul>

            <?php include 'form.edit.pages.actions.tpl.php'; ?>
        </li>
    </ul>
</div>
