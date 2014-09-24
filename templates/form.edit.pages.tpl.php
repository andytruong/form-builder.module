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

            <div class="form-item form-type-textfield">
                <label>Page name</label>
                <input type="text" ng-model="entity.layoutOptions.pages[pageUuid].title" class="form-text" />
            </div>

            <div class="form-item form-type-textarea form-item-description">
                <div class="form-textarea-wrapper">
                    <textarea ng-model="entity.layoutOptions.pages[pageUuid].description"
                              placeholder="Description…"
                              class="text-full form-textarea required"></textarea>
                </div>
            </div>

            <div class="page-actions">
                <a href ng-click="pageRemove(pageUuid)">Remove page</a>
            </div>

            <ul>
                <?php include 'form.edit.fields.item.tpl.php'; ?>
            </ul>
        </li>
    </ul>
</div>
