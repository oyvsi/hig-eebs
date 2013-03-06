<?php

/**
 * Description of ReportController
 *
 * @author Team Henkars
 */
class ReportController extends BaseController {

   private $datamodel;
   private $tables = array('comments' => array('tableName' => 'commentReports', 'reportField' => 'commentID'),
       'blogpost' => array('tableName' => 'blogPostReports', 'reportField' => 'postID'));

   /**
   * constructor. used to set up the model info for reports.
   */
   public function __construct() {
      parent::__construct();
      $this->model = new ReportModel();
   }

   /**
   * function to create a raport on an users post. 
   * creates a form with a comment field.
   * Arguments from url: blogPostID and what the report is for
   * 
   * @url report/report/$type/$blogpostID
   */
   public function report() {
      print_r($this->args);
      if (in_array($this->args[1], $this->tables)) {
         echo "found";
      }
      if (isset($this->args[1]) && isset($this->args[2]) && array_key_exists($this->args[1], $this->tables)) {
         $form = new Form('report', 'report/reportDo/', 'POST');
         $form->addTextArea('reportText', 10, 60, 'Description of violation');
         $form->addInput('hidden', 'id', false, $this->args[2]);
         $form->addInput('hidden', 'table', false, $this->args[1]);
         $form->addInput('submit', 'submit');
         $this->view->setVar('title', 'Report');
         $this->view->setVar('form', $form->genForm());
         $this->view->addViewFile('report');
      }
   }

   /**
   * Fuction that process report data and gives message to 
   * reporting user. Gets data from POST. 
   */
   public function reportDo() {
      if (isset($_POST['reportText'])) {
         try {
            $table = $this->tables[$_POST['table']]['tableName'];
            $field = $this->tables[$_POST['table']]['reportField'];
            $this->model->report($_POST['id'], $table, $field, $_POST);
            $this->view->setVar('message', 'Thank you. Your report will be brought to the administrators');
         } catch (Exception $excpt) {
            $this->view->setError($excpt);
         }
      }
   }
}

?>
