<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiscountCode;
use App\Support\AdminAudit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DiscountCodeController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $status = trim((string) $request->query('status', ''));

        $discountCodes = DiscountCode::query()
            ->when($search !== '', fn ($query) => $query->where('code', 'like', '%' . $search . '%'))
            ->when($status === 'active', fn ($query) => $query->where('is_active', true))
            ->when($status === 'inactive', fn ($query) => $query->where('is_active', false))
            ->latest()
            ->paginate(15)
            ->appends($request->query());

        return view('admin.discount-codes.index', compact('discountCodes', 'search', 'status'));
    }

    public function create()
    {
        $types = DiscountCode::TYPES;

        return view('admin.discount-codes.create', compact('types'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['code'] = strtoupper(trim((string) $data['code']));
        $data['is_active'] = $request->boolean('is_active');

        $discountCode = DiscountCode::create($data);

        AdminAudit::log(
            'discount_code.created',
            $discountCode,
            'Created discount code ' . $discountCode->code . '.',
            ['type' => $discountCode->type, 'value' => $discountCode->value]
        );

        return redirect()->route('admin.discount-codes.index')
            ->with('status', 'Discount code created successfully.');
    }

    public function edit(DiscountCode $discountCode)
    {
        $types = DiscountCode::TYPES;

        return view('admin.discount-codes.edit', compact('discountCode', 'types'));
    }

    public function update(Request $request, DiscountCode $discountCode)
    {
        $before = $discountCode->only(['code', 'type', 'value', 'is_active', 'usage_limit', 'starts_at', 'ends_at']);
        $data = $this->validateData($request, $discountCode);
        $data['code'] = strtoupper(trim((string) $data['code']));
        $data['is_active'] = $request->boolean('is_active');

        $discountCode->update($data);

        AdminAudit::log(
            'discount_code.updated',
            $discountCode,
            'Updated discount code ' . $discountCode->code . '.',
            ['before' => $before, 'after' => $discountCode->only(['code', 'type', 'value', 'is_active', 'usage_limit', 'starts_at', 'ends_at'])]
        );

        return redirect()->route('admin.discount-codes.index')
            ->with('status', 'Discount code updated successfully.');
    }

    public function destroy(DiscountCode $discountCode)
    {
        $code = $discountCode->code;
        $discountCodeId = $discountCode->id;
        $discountCode->delete();

        AdminAudit::log(
            'discount_code.deleted',
            'DiscountCode',
            'Deleted discount code ' . $code . '.',
            ['discount_code_id' => $discountCodeId, 'code' => $code]
        );

        return redirect()->route('admin.discount-codes.index')
            ->with('status', 'Discount code deleted successfully.');
    }

    protected function validateData(Request $request, ?DiscountCode $discountCode = null): array
    {
        return $request->validate([
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('discount_codes', 'code')->ignore($discountCode?->id),
            ],
            'type' => ['required', Rule::in(array_keys(DiscountCode::TYPES))],
            'value' => ['required', 'numeric', 'min:0.01'],
            'is_active' => ['nullable', 'boolean'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);
    }
}
