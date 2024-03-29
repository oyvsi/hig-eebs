<?php
/**
 * Report database functions
 *
 * @author Team Henkars
 */
class ReportModel extends BaseModel {
   protected $reportFields=  array('reportText' => array('view' => 'Text', 'minLength' => 3, 'maxLength' => 100));

	/**
	* Function that insert reports to database
	* 
	* @param int $id
	* @param string $table
	* @param string $field
	* @param array $data			
	*/
   public function report($id, $table, $field, $data) {
      $valid = new ValidateForm($data);
      $valid->setRequired($this->reportFields);
      
      if (Auth::CheckLogin() === false) {
         throw new Exception('Can\'t report blog post when you\'re not logged in');
      }

      if ($valid->check() === false) {
         $errors = implode('<br />', $valid->getErrors());
         throw new Exception($errors);
      }

      $query = 'INSERT INTO ' . $table . '(' . $field . ', userID, reportText, timestamp) VALUES(:fieldID, :userID, :reportText, :timestamp)';
      $this->db->insert($query, array(':fieldID' => $id, ':userID' => $_SESSION['userID'], ':reportText' => $data['reportText'], ':timestamp' => time()));
   }

}

?>
