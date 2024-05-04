<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Inani\Larapoll\Poll;

use Auth;

use App\Models\News;
use App\Services\NewsService;

use App\Http\Controllers\Controller;

class NewsController extends Controller
{
    /**
     * Shows the news index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getIndex()
    {
        return view('admin.news.news', [
            'newses' => News::orderBy('updated_at', 'DESC')->paginate(20)
        ]);
    }
    
    /**
     * Shows the create news page. 
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCreateNews()
    {
        return view('admin.news.create_edit_news', [
            'news' => new News
        ]);
    }
    
    /**
     * Shows the edit news page.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEditNews($id)
    {
        $news = News::find($id);
        if(!$news) abort(404);
        return view('admin.news.create_edit_news', [
            'news' => $news
        ]);
    }

    /**
     * Shows the create poll page. 
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCreatePoll()
    {
        return view('admin.news.create_edit_poll', [
            'poll' => new Poll
        ]);
    }
    
    /**
     * Shows the edit poll page.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEditPoll($id)
    {
        $poll = Poll::find($id);
        if(!$poll) abort(404);
        return view('admin.news.create_edit_poll', [
            'poll' => $poll
        ]);
    }

    /**
     * Creates or edits a news page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Services\NewsService  $service
     * @param  int|null                  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateEditNews(Request $request, NewsService $service, $id = null)
    {
        $id ? $request->validate(News::$updateRules) : $request->validate(News::$createRules);
        $data = $request->only([
            'title', 'text', 'post_at', 'is_visible', 'bump'
        ]);
        if($id && $service->updateNews(News::find($id), $data, Auth::user())) {
            flash('News updated successfully.')->success();
        }
        else if (!$id && $news = $service->createNews($data, Auth::user())) {
            flash('News created successfully.')->success();
            return redirect()->to('admin/news/edit/'.$news->id);
        }
        else {
            foreach($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->back();
    }

    public function postCreateEditPoll(Request $request, NewsService $service)
    {
        try {
            $data = $request->only(['title', 'question', 'option_1', 'option_2', 'option_3', 'option_4', 'option_5']);
            $poll = new Poll([
                'question' => parse($data['question'])
            ]); 
            $options = array();
            if (parse($data['option_1'] != '')) {
                array_push($options, parse($data['option_1']));
            }
            if (parse($data['option_2'] != '')) {
                array_push($options, parse($data['option_2']));
            }
            if (parse($data['option_3'] != '')) {
                array_push($options, parse($data['option_3']));
            }
            if (parse($data['option_4'] != '')) {
                array_push($options, parse($data['option_4']));
            }
            if (parse($data['option_5'] != '')) {
                array_push($options, parse($data['option_5']));
            }
            $poll->addOptions($options)->generate();
            $data['poll_id'] = $poll->id;
            $news = $service->createNewsWithPoll($data, Auth::user());
            flash('News with poll created successfully.')->success();
        } catch(\Exception $e) { 
            flash($e->getMessage())->error();
        }
        
        return redirect()->back();
    }
    
    /**
     * Gets the news deletion modal.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getDeleteNews($id)
    {
        $news = News::find($id);
        return view('admin.news._delete_news', [
            'news' => $news,
        ]);
    }

    /**
     * Deletes a news page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Services\NewsService  $service
     * @param  int                       $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteNews(Request $request, NewsService $service, $id)
    {
        if($id && $service->deleteNews(News::find($id))) {
            flash('News deleted successfully.')->success();
        }
        else {
            foreach($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->to('admin/news');
    }

}
