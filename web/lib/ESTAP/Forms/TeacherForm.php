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
    public $times;
    public $duration;
    public $startHour;
    public $startMinute;
    public $endHour;
    public $endMinute;
    public $day;
    public $month;
    public $year;
    /**
     * The meeting link.
     *
     * @var string
     */
    public $vConferenceLink;
    /**
     * The meeting id.
     *
     * @var string
     */
    public $vConferenceId;
    /**
     * The meeting pass.
     *
     * @var string
     */
    public $vConferencePass;

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
            $this->vConferenceLink = $teacher->getVConferenceLink();
            $this->vConferenceId = $teacher->getVConferenceId();
            $this->vConferencePass = $teacher->getVConferencePass();
        } else {
            $this->room = "";
            $this->gender = "f";
            $this->vConferenceLink = "";
            $this->vConferenceId = "";
            $this->vConferencePass = "";
        }
    }
}
