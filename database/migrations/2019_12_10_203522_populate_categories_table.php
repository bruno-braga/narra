<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PopulateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('categories')->insert([
            ['name' => 'Arts', 'parent_id' => null],
            ['name' => 'Business', 'parent_id' => null],
            ['name' => 'Comedy', 'parent_id' => null],
            ['name' => 'Fiction', 'parent_id' => null],
            ['name' => 'Governament', 'parent_id' => null],
            ['name' => 'History', 'parent_id' => null],
            ['name' => 'Health & Fitness', 'parent_id' => null], // 7
            ['name' => 'Kids & Family', 'parent_id' => null], // 8
            ['name' => 'Leisure', 'parent_id' => null], // 9
            ['name' => 'Music', 'parent_id' => null], // 10
            ['name' => 'News', 'parent_id' => null], // 11
            ['name' => 'Religion & Spirituality', 'parent_id' => null], // 12
            ['name' => 'Science', 'parent_id' => null], // 13
            ['name' => 'Society & Culture', 'parent_id' => null], // 14
            ['name' => 'Sports', 'parent_id' => null], // 15
            ['name' => 'Technology', 'parent_id' => null], // 16
            ['name' => 'True Crime', 'parent_id' => null], // 17
            ['name' => 'TV & Film', 'parent_id' => null], // 18

            ['name' => 'Books', 'parent_id' => 1],
            ['name' => 'Design', 'parent_id' => 1],
            ['name' => 'Fashion & Beauty', 'parent_id' => 1],
            ['name' => 'Food', 'parent_id' => 1],
            ['name' => 'Performing Arts', 'parent_id' => 1],
            ['name' => 'Visual Arts', 'parent_id' => 1],

            ['name' => 'Careers', 'parent_id' => 2],
            ['name' => 'Entrepreneurship', 'parent_id' => 2],
            ['name' => 'Management', 'parent_id' => 2],
            ['name' => 'Marketing', 'parent_id' => 2],
            ['name' => 'Non-profit', 'parent_id' => 2],

            ['name' => 'Courses', 'parent_id' => 3],
            ['name' => 'How to', 'parent_id' => 3],
            ['name' => 'Language Learning', 'parent_id' => 3],
            ['name' => 'Self Improvement', 'parent_id' => 3],

            ['name' => 'Comedy Fiction', 'parent_id' => 4],
            ['name' => 'Drama', 'parent_id' => 4],
            ['name' => 'Science Fiction', 'parent_id' => 4],

            ['name' => 'Alternative Health', 'parent_id' => 7],
            ['name' => 'Fitness', 'parent_id' => 7],
            ['name' => 'Medicine', 'parent_id' => 7],
            ['name' => 'Mental Health', 'parent_id' => 7],
            ['name' => 'Nutrition', 'parent_id' => 7],
            ['name' => 'Sexutality', 'parent_id' => 7],

            ['name' => 'Education for Kids', 'parent_id' => 8],
            ['name' => 'Parenting', 'parent_id' => 8],
            ['name' => 'Pets & Animals', 'parent_id' => 8],
            ['name' => 'Story for Kids', 'parent_id' => 8],

            ['name' => 'Animation & Manga', 'parent_id' => 9],
            ['name' => 'Automotive', 'parent_id' => 9],
            ['name' => 'Aviation', 'parent_id' => 9],
            ['name' => 'Crafts', 'parent_id' => 9],
            ['name' => 'Games', 'parent_id' => 9],
            ['name' => 'Hobbies', 'parent_id' => 9],
            ['name' => 'Home & Garden', 'parent_id' => 9],
            ['name' => 'Video Games', 'parent_id' => 9],
            ['name' => 'Story for Kids', 'parent_id' => 9],

            ['name' => 'Music Comentary', 'parent_id' => 10],
            ['name' => 'Music History', 'parent_id' => 10],
            ['name' => 'Music Interviews', 'parent_id' => 10],

            ['name' => 'Business News', 'parent_id' => 11],
            ['name' => 'Daily News', 'parent_id' => 11],
            ['name' => 'Entertainment News', 'parent_id' => 11],
            ['name' => 'News Comentary', 'parent_id' => 11],
            ['name' => 'Politics', 'parent_id' => 11],
            ['name' => 'Sports News', 'parent_id' => 11],
            ['name' => 'Tech News', 'parent_id' => 11],

            ['name' => 'Buddhism', 'parent_id' => 12],
            ['name' => 'Christianity', 'parent_id' => 12],
            ['name' => 'Hinduism', 'parent_id' => 12],
            ['name' => 'Islam', 'parent_id' => 12],
            ['name' => 'Judaism', 'parent_id' => 12],
            ['name' => 'Religion', 'parent_id' => 12],
            ['name' => 'Spirituality', 'parent_id' => 12],

            ['name' => 'Astronomy', 'parent_id' => 13],
            ['name' => 'Chemestry', 'parent_id' => 13],
            ['name' => 'Earth Sciences', 'parent_id' => 13],
            ['name' => 'Life Sciences', 'parent_id' => 13],
            ['name' => 'Mathematics', 'parent_id' => 13],
            ['name' => 'Natural Sciences', 'parent_id' => 13],
            ['name' => 'Nature', 'parent_id' => 13],
            ['name' => 'Physics', 'parent_id' => 13],
            ['name' => 'Social Sciences', 'parent_id' => 13],

            ['name' => 'Documentary', 'parent_id' => 14],
            ['name' => 'Personal Journals', 'parent_id' => 14],
            ['name' => 'Philosophy', 'parent_id' => 14],
            ['name' => 'Places & Travel', 'parent_id' => 14],
            ['name' => 'Relationship', 'parent_id' => 14],

            ['name' => 'Baseball', 'parent_id' => 15],
            ['name' => 'Basketball', 'parent_id' => 15],
            ['name' => 'Cricket', 'parent_id' => 15],
            ['name' => 'Fantasy Sports', 'parent_id' => 15],
            ['name' => 'Football', 'parent_id' => 15],
            ['name' => 'Golf', 'parent_id' => 15],
            ['name' => 'Hockey', 'parent_id' => 15],
            ['name' => 'Rugby', 'parent_id' => 15],
            ['name' => 'Running', 'parent_id' => 15],
            ['name' => 'Soccer', 'parent_id' => 15],
            ['name' => 'Swimming', 'parent_id' => 15],
            ['name' => 'Tenis', 'parent_id' => 15],
            ['name' => 'Volleyball', 'parent_id' => 15],
            ['name' => 'Wilderness', 'parent_id' => 15],
            ['name' => 'Wrestling', 'parent_id' => 15],

            ['name' => 'After Shows', 'parent_id' => 18],
            ['name' => 'Film History', 'parent_id' => 18],
            ['name' => 'Film Interviews', 'parent_id' => 18],
            ['name' => 'Film Reviews', 'parent_id' => 18],
            ['name' => 'TV Reviews', 'parent_id' => 18],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
