<?php namespace App\Services;

use App\Services\Service;

use DB;
use Config;

use App\Models\User\User;
use App\Models\News;

use Carbon\Carbon;

use Inani\Larapoll\Poll;

class NewsService extends Service
{
    /*
    |--------------------------------------------------------------------------
    | News Service
    |--------------------------------------------------------------------------
    |
    | Handles the creation and editing of news posts.
    |
    */

    /**
     * Creates a news post.
     *
     * @param  array                  $data
     * @param  \App\Models\User\User  $user
     * @return bool|\App\Models\News
     */
    public function createNews($data, $user)
    {
        DB::beginTransaction();

        try {
            $data['parsed_text'] = parse($data['text']);
            $data['user_id'] = $user->id;
            if(!isset($data['is_visible'])) $data['is_visible'] = 0;

            $news = News::create($data);

            if($news->is_visible) $this->alertUsers();

            return $this->commitReturn($news);
        } catch(\Exception $e) { 
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Creates a news post with a poll.
     *
     * @param  array                  $data
     * @param  \App\Models\User\User  $user
     * @return bool|\App\Models\News
     */
    public function createNewsWithPoll($data, $user)
    {
        DB::beginTransaction();

        try {
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
            $poll->addOptions($options)->endsAt($data['closing_time'])->generate();
            $newsData = ['title', 'parsed_text', 'text', 'is_visible', 'user_id', 'poll_id'];  
            $newsData['title'] = parse($data['title']);
            $newsData['parsed_text'] = parse($data['question']); 
            $newsData['text'] = parse($data['question']); 
            $newsData['is_visible'] = 1;
            $newsData['user_id'] = $user->id;
            $newsData['poll_id'] = $poll->id;
            $news = News::create($newsData);
            if($news->is_visible) $this->alertUsers();
            return $this->commitReturn($news);
        } catch(\Exception $e) { 
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Updates a news post.
     *
     * @param  \App\Models\News       $news
     * @param  array                  $data 
     * @param  \App\Models\User\User  $user
     * @return bool|\App\Models\News
     */
    public function updateNews($news, $data, $user)
    {
        DB::beginTransaction();

        try {
            $data['parsed_text'] = parse($data['text']);
            $data['user_id'] = $user->id;
            if(!isset($data['is_visible'])) $data['is_visible'] = 0;
            if(isset($data['bump']) && $data['is_visible'] == 1 && $data['bump'] == 1) $this->alertUsers();

            $news->update($data);

            return $this->commitReturn($news);
        } catch(\Exception $e) { 
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Deletes a news post.
     *
     * @param  \App\Models\News  $news
     * @return bool
     */
    public function deleteNews($news)
    {
        DB::beginTransaction();

        try {
            $news->delete();

            return $this->commitReturn(true);
        } catch(\Exception $e) { 
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Updates queued news posts to be visible and alert users when
     * they should be posted.
     *
     * @return bool
     */
    public function updateQueue()
    {
        $count = News::shouldBeVisible()->count();
        if($count) {
            DB::beginTransaction();

            try {
                News::shouldBeVisible()->update(['is_visible' => 1]);
                $this->alertUsers();

                return $this->commitReturn(true);
            } catch(\Exception $e) { 
                $this->setError('error', $e->getMessage());
            }
            return $this->rollbackReturn(false);
        }
    }

    /**
     * Updates the unread news flag for all users so that
     * the new news notification is displayed.
     *
     * @return bool
     */
    private function alertUsers()
    {
        User::query()->update(['is_news_unread' => 1]);
        return true;
    }
}