<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 */

namespace ESTAP\Forms;

use ESTAP\User;
use PhoolKit\RequireValidator;

/**
 * Form for teacher data.
 *
 * @author Klaus Reimer <k@ailis.de>
 */
class TeacherForm extends UserForm
{
    /**
     * The gender.
     *
     * @var string
     */
    public $gender;

    /**
     * The room.
     *
     * @var string
     */
    public $room;
    //TODO Jdoc
    public $times;
    public $duration;
    public $startHour;
    public $startMinute;
    public $endHour;
    public $endMinute;
    public $day;
    public $month;
    public $year;
    public $vcLink;

    /**
     * @see PhoolKit.Form::getValidators()
     */
    public function getValidators()
    {
        $validators = parent::getValidators();
        $validators[] = new RequireValidator("gender");
        return $validators;
    }

    /**
     * @see PhoolKit.Form::init()
     */
    public function init(User $teacher = null)
    {
        parent::init($teacher);

        if ($teacher) {
            $this->room = $teacher->getRoom();
            $this->gender = $teacher->getGender();
            $this->vcLink = $teacher->getVCLink();
        } else {
            $this->room = "";
            $this->gender = "f";
            $this->vcLink = "";
        }
    }
}
