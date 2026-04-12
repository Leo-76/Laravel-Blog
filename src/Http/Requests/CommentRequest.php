<?php

namespace CafeHistoire\CafeBlog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return config('cafe-blog.comments_enabled', true);
    }

    public function rules(): array
    {
        return [
            'author_name'    => ['required', 'string', 'min:2', 'max:100'],
            'author_email'   => ['required', 'email', 'max:200'],
            'author_website' => ['nullable', 'url', 'max:200'],
            'content'        => ['required', 'string', 'min:10', 'max:2000'],
            'parent_id'      => ['nullable', 'integer', 'exists:cafe_blog_comments,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'author_name.required'  => __('cafe-blog::blog.comment_name') . ' est requis.',
            'author_email.required' => __('cafe-blog::blog.comment_email') . ' est requis.',
            'author_email.email'    => 'Adresse e-mail invalide.',
            'content.required'      => 'Le commentaire est requis.',
            'content.min'           => 'Le commentaire doit contenir au moins 10 caractères.',
            'content.max'           => 'Le commentaire ne peut pas dépasser 2 000 caractères.',
        ];
    }
}
