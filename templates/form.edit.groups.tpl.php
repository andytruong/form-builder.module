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
</div>
