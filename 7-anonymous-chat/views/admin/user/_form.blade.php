<div class="form-group row">
    <label class="col-sm-12 col-md-2 col-form-label">Name</label>
    <div class="col-sm-12 col-md-10">
        <input class="form-control" type="text" name="name" placeholder="Enter your name"
               value="{{ old('name', $user->name) }}">
    </div>

    @error('name')
    <p class="text-danger">{{ $message }}</p>
    @enderror
</div>

<div class="form-group row">
    <label class="col-sm-12 col-md-2 col-form-label">Chat id</label>
    <div class="col-sm-12 col-md-10">
        <input class="form-control" type="text" name="chat_id" placeholder="Enter Chat id"
               value="{{ old('chat_id', $user->chat_id) }}">
    </div>

    @error('chat_id')
    <p class="text-danger">{{ $message }}</p>
    @enderror
</div>

<div class="form-group row">
    <label class="col-sm-12 col-md-2 col-form-label">Address</label>
    <div class="col-sm-12 col-md-10">
        <input class="form-control" type="text" name="address" placeholder="Enter Chat id"
               value="{{ old('address', $user->address) }}">
    </div>

    @error('address')
    <p class="text-danger">{{ $message }}</p>
    @enderror
</div>

<div class="form-group row">
    <button class="btn btn-primary">{{ $buttonText ?? 'Create' }}</button>
</div>