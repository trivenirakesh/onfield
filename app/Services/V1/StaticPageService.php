<?php

namespace App\Services\V1;

use Illuminate\Http\Request;
use App\Traits\CommonTrait;
use App\Models\StaticPage;

class StaticPageService
{
    use CommonTrait;
    const module = 'Page';


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $page = StaticPage::where('slug', $slug)->first();
        if ($page == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'), 401);
        }
        return $this->successResponseArr(self::module . __('messages.success.details'), $page);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $post_data = $request->only('slug', 'content');
        $post_data['title'] = StaticPage::PAGES[$request->slug];
        $page = StaticPage::updateOrCreate(['slug' => $post_data['slug']], $post_data);
        return $this->successResponseArr(self::module . __('messages.success.update'));
    }
}
