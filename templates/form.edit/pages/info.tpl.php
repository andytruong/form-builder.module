<div class="form-item form-type-textfield">
  <label>Page name</label>
  <input type="text"
         class="form-text"
         ng-model-options="{ updateOn: 'blur' }"
         ng-model="entity.layoutOptions.pages[pageUuid].title"/>
</div>

<div class="form-item form-type-textarea form-item-description">
  <div class="form-textarea-wrapper">
    <textarea ng-model="entity.layoutOptions.pages[pageUuid].description"
              ng-model-options="{ updateOn: 'blur' }"
              placeholder="Description…"
              class="text-full form-textarea required"></textarea>
  </div>
</div>
