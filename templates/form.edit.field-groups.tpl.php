<div class="form-item form-type-textfield">
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

    <input type="text"
           id="edit-new-group-title"
           name="title"
           size="60"
           maxlength="255"
           class="form-text"
           placeholder="Group name…"
           ng-model="newGroupTitle" />
    <button ng-click="pageGroup()">
        Add<span ng-if="newGroupAdding">ing…</span>
    </button>
</div>
