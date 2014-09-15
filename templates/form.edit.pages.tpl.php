<label>Form fields</label>

<div class="item-list">
    <ul>
        <li ng-repeat="(pageUuid, pageInfo) in entity.layoutOptions">

            <div class="form-item form-type-textfield">
                <input type="text" ng-model="pageInfo.title" class="form-text" />
            </div>

            <div class="form-item form-type-textarea form-item-description">
                <div class="form-textarea-wrapper">
                    <textarea ng-model="pageInfo.description"
                              placeholder="Description…"
                              class="text-full form-textarea required"></textarea>
                </div>
            </div>

            <div class="page-actions">
                <a href ng-click="pageRemove(pageUuid)">Remove</a>
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
