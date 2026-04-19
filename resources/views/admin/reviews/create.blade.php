@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <h1 class="h3 mb-4 text-gray-800">Add New Testimonial</h1>
            
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Testimonial Details</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.reviews.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="form-group">
                            <label>Type</label>
                            <select name="type" class="form-control" required>
                                <option value="whatsapp">WhatsApp Screenshot</option>
                                <option value="instagram">Instagram Screenshot</option>
                                <option value="external">External Link Screenshot</option>
                                <option value="video">YouTube Video Link</option>
                            </select>
                        </div>

                        <div id="imageField" class="form-group">
                            <label>Upload Screenshot</label>
                            <input type="file" name="image" class="form-control">
                            <small class="text-muted">Upload the image of the testimonial.</small>
                        </div>

                        <div class="form-group">
                            <label id="contentLabel">Description (Optional)</label>
                            <textarea name="content" class="form-control" rows="3" placeholder="Enter description or YouTube link..."></textarea>
                            <small class="text-muted" id="contentHelp">For screenshots, this is an optional description. For videos, this must be the YouTube link.</small>
                        </div>

                        <button type="submit" class="btn btn-primary">Save Testimonial</button>
                        <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const typeSelect = document.querySelector('select[name="type"]');
    const imageField = document.getElementById('imageField');
    const label = document.getElementById('contentLabel');
    const help = document.getElementById('contentHelp');

    typeSelect.addEventListener('change', function() {
        if (this.value === 'video') {
            imageField.style.display = 'none';
            label.textContent = 'YouTube Video Link';
            help.textContent = 'Enter the full YouTube URL (e.g., https://www.youtube.com/watch?v=...)';
        } else {
            imageField.style.display = 'block';
            label.textContent = 'Description (Optional)';
            help.textContent = 'Enter an optional description for this screenshot.';
        }
    });
</script>
@endsection
