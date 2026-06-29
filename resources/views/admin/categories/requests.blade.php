@extends('layouts.adminDashboard')

@section('title')
    <title>Category Requests | Admin Dashboard</title>
@endsection

@section('style')
<style>
    .requests-table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; }
    .requests-table th, .requests-table td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
    .requests-table th { background: #f8f9fa; font-weight: 600; }
    .requests-table tbody tr:hover { background: #f8f9fa; }
    .btn { padding: 8px 12px; border-radius: 5px; border: none; cursor: pointer; font-size: 0.85rem; }
    .btn-approve { background: #00b894; color: white; }
    .btn-reject { background: #ff7675; color: white; }
    .btn-approve:hover { background: #009e7a; }
    .btn-reject:hover { background: #d63031; }
    .status-badge { display: inline-block; padding: 5px 10px; border-radius: 3px; font-size: 0.85rem; font-weight: 600; }
    .status-pending { background: #fdcb6e; color: #2d3436; }
    .status-approved { background: #00b894; color: white; }
    .status-rejected { background: #ff7675; color: white; }
    header { margin-bottom: 25px; border-left: 4px solid #ffeaa7; padding-left: 15px; }
    .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); }
    .modal.show { display: flex; align-items: center; justify-content: center; }
    .modal-content { background: white; padding: 30px; border-radius: 8px; width: 90%; max-width: 500px; }
    .modal-header { font-weight: 600; font-size: 1.2rem; margin-bottom: 15px; }
    .modal-actions { display: flex; gap: 10px; margin-top: 20px; }
    .modal-close { cursor: pointer; color: #999; font-size: 1.5rem; position: absolute; top: 10px; right: 10px; }
</style>
@endsection

@section('content')
<header>
    <h1><i class="fas fa-inbox"></i> Category Requests</h1>
    <p>Manage seller category requests</p>
</header>

@if($message = Session::get('success'))
    <div style="padding: 15px; background: #00b894; color: white; border-radius: 5px; margin-bottom: 20px;">
        {{ $message }}
    </div>
@endif

<table class="requests-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Seller</th>
            <th>Requested Category</th>
            <th>Status</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($requests as $request)
        <tr>
            <td>{{ $request->id }}</td>
            <td>{{ $request->seller->name ?? 'N/A' }}</td>
            <td>
                <strong>{{ $request->category_name }}</strong><br>
                <small style="color: #666;">{{ \Str::limit($request->description, 50) }}</small>
            </td>
            <td>
                <span class="status-badge status-{{ $request->status }}">{{ ucfirst($request->status) }}</span>
            </td>
            <td>{{ $request->created_at->format('M d, Y') }}</td>
            <td>
                @if($request->status == 'pending')
                    <button class="btn btn-approve" onclick="approveRequest({{ $request->id }})">Approve</button>
                    <button class="btn btn-reject" onclick="showRejectModal({{ $request->id }})">Reject</button>
                @else
                    <span style="color: #999; font-size: 0.9rem;">{{ ucfirst($request->status) }}</span>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" style="text-align: center; padding: 30px;">No category requests yet.</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top: 20px;">
    {{ $requests->links() }}
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="modal">
    <div class="modal-content">
        <span class="modal-close" onclick="closeRejectModal()">&times;</span>
        <div class="modal-header">Reject Category Request</div>
        
        <form id="rejectForm" method="POST" action="">
            @csrf
            
            <label for="admin_notes">Reason for Rejection (optional):</label>
            <textarea name="admin_notes" id="admin_notes" rows="4" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" placeholder="Explain why this category request is rejected..."></textarea>
            
            <div class="modal-actions">
                <button type="submit" class="btn btn-reject">Reject Request</button>
                <button type="button" class="btn" style="background: #ddd; color: #2d3436;" onclick="closeRejectModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function approveRequest(requestId) {
    if (confirm('Approve this category request?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ url("admin/category-requests") }}/' + requestId + '/approve';
        form.innerHTML = '@csrf';
        document.body.appendChild(form);
        form.submit();
    }
}

function showRejectModal(requestId) {
    document.getElementById('rejectForm').action = '{{ url("admin/category-requests") }}/' + requestId + '/reject';
    document.getElementById('rejectModal').classList.add('show');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.remove('show');
}
</script>
@endsection
