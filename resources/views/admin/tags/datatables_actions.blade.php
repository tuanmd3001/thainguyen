
{!! Form::open(['route' => ['admin.tags.destroy', $tag->id], 'method' => 'delete']) !!}
<div class='btn-group'>
    <button type="button" onclick="edit_tag({{ $tag->id }}, '{{ $tag->getTranslation('name', 'vi') }}')" data-id="{{ $tag->id }}" data-name="{{ $tag->getTranslation('name', 'vi') }}" class='btn btn-default btn-xs tag_edit_btn'>
        <i class="glyphicon glyphicon-edit"></i>
    </button>
    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', [
        'type' => 'submit',
        'class' => 'btn btn-danger btn-xs',
        'onclick' => "return confirm('Chắc chắn xóa?')"
    ]) !!}
</div>
{!! Form::close() !!}
