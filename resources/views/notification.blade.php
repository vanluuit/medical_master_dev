@if (count($errors) > 0)
    <div class="chatter-alert alert alert-danger">
        <div class="container">
            <p><strong><i class="chatter-alert-danger"></i></strong> Please fix the following errors:</p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif