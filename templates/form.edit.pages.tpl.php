<div class="item-list">
    <ul>
        <li ng-repeat="page in pages|orderBy:'weight'" ng-init="pageUuid = page.uuid">
            <div class="form-item form-type-textfield">
                <label>Page name</label>
                <input type="text" ng-model="entity.layoutOptions.pages[pageUuid].title" class="form-text" />
            </div>

            <div class="form-item form-type-textfield">
                <label>Weight</label>
                <input type="text" ng-model="entity.layoutOptions.pages[pageUuid].weight" class="form-text" />
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

<div class="form-item form-type-textfield form-item-new-page-title">
    <input type="text"
           id="edit-new-page-title"
           name="title"
           size="60"
           maxlength="255"
           class="form-text"
           placeholder="Page name…"
           ng-model="newPageTitle" />
    <button ng-click="pageNew()">
        Add<span ng-if="newPageAdding">ing…</span>
    </button>
</div>
