<label>Form fields</label>

<div class="item-list">
    <ul>
        <li ng-repeat="(pageUuid, pageInfo) in entity.layoutOptions">
            <h2>{{pageInfo.title}}</h2>
            <div class="description">{{pageInfo.description}}</div>
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
    <button ng-click="newPageClick()">
        Add<span ng-if="newPageAdding">ing…</span>
    </button>
</div>
