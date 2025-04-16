
<div class="tree-node {{ $account->parent_id == 0 ? 'main-node' : '' }} collapsed" data-id="{{ $account->id }}">
    <div class="node-content">
        <i class="{{ count($account->children ?? []) > 0 ? 'fas fa-folder' : 'fas fa-file' }}"></i>
        <div class="node-info">
            <span class="account-name">{{ $account->account_number }} - {{ $account->name }}  </span>
            <div class="account-details">
                <span class="account-balance">{{ number_format($account->getBalanceDetails(), 2) }} ريال</span>
                <span class="account-type" data-type="{{ $account->accountType->name }}">
                    {{ $account->accountType->name }}
                </span>
            </div>
        </div>
        <div class="node-actions">
            <button class="show-account-btn" id="{{ $account->id}}" title="عرض الحساب">
                <i class="fas fa-eye"></i>
            </button>

            <button class="add-sub-account" title="إضافة حساب فرعي">
                <i class="fas fa-plus"></i>
            </button>
            @if(count($account->children ?? []) > 0)
                <button class="toggle-node" title="توسيع/طي">
                    <i class="fas fa-chevron-down"></i>
                </button>
            @endif
        </div>
    </div>

    @if (!empty($account->children))
        <div class="sub-nodes">
            @foreach ($account->children as $child)
                @include('financialaccounting.accountsTree.treeNode', ['account' => $child])
            @endforeach
        </div>
    @endif
</div>
