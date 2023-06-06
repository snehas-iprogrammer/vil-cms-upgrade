<?php
/**
 * The class to present GameScreen model.
 * 
 * 
 * @author Sneha Shete<snehas@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */

namespace Modules\Admin\Models;

class GameScreen extends BaseModel
{

    /**
     * The daGameScreenase GameScreenle used by the model.
     *
     * @var string
     */
    protected $gamescreen = 'game_screen';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','section_name','rank','status'];
}
