<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'comment_text' => $this->text(),
        ];
    }

    public function text()
    {
        $comments = [
            'Nice to read',
            'Wow I am really into this',
            'So much fun to read',
            'The latest chapter made me sad',
            'No spoilers please!',
            'Like this comment if you think this comic is cooOoOol',
            'I can not be the only one smiling like an idiot while reading this',
            'And here I am wondering why did I start reading it just now?! Amazing',
            'What a masterpiece',
            'Yo. I\'m from the future, trust me this is gold',
            'I\'m confused reading this story',
            'it\'s really interesting to read',
            'It takes a great artist and storyteller to make a story without any words.',
            'This is so cute',
            'Dude I\'ve never related to a comic more',
            'Now that just put a smile on my sleepy face XD',
            'I felt this comic today especially',
            'IM NOT CRYING YOU ARE',
        ];

        return $comments[random_int(0, (count($comments) - 1))];
    }
}
