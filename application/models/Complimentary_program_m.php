<?php


class Complimentary_program_m extends MY_Model
{
    protected $table = "complimentary_program";

    public function gridConfig($option = array())
    {
        $countParticipantQuery = "SELECT COUNT(td.id) AS countParticipant, td.program_id FROM compli_program_parcitipant td
		GROUP BY td.program_id";

        return [
            'relationships' => [
                'countParticipant' => ["({$countParticipantQuery})", 'countParticipant.program_id = t.id', 'left'],
            ],
        ];
    }

    public function joinCountParticipant()
    {
        $countParticipantQuery = "SELECT COUNT(td.id) AS countParticipant, td.program_id FROM compli_program_parcitipant td
		GROUP BY td.program_id";
        return $this->find()->join("($countParticipantQuery) as c", "c.program_id = complimentary_program.id", "left");
    }
}
