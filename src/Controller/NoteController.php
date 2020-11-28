<?php 
declare(strict_types=1);



namespace App\Controller;

use App\Exception\NotFoundException;


class NoteController extends AbstractController{

    
    public function createAction()
    {     
      
                      
      if ($this->request->hasPost()) { // jeżeli są dane w tablicy superglobalnej post 
      $noteData = [
        'title'=>$this->request->postParam('title'),
        'description'=>$this->request->postParam('description'),                
      ];

      $this->database->createNote($noteData);

      header("Location: /?before=created");
      
      exit;
      
      }
    $this->view->render('create'); 
    }

    public function showAction()
    {

      $noteId =(int) $this->request->getParam('id'); // rzutowanie na inta



      if(!$noteId){
        header("Location: /?error=missingNoteId");
        exit;
      }

      try{
        $note = $this->database->getNote($noteId);
      }catch(NotFoundException $e){                
        header("Location: /?error=noteNotFound");
        exit;
      }

      $viewParams = [
      "note"=>$note,
      ];
      $this->view->render('show',
      ['note' =>$note]
    ); 
    }

    public function listAction()
    {
            
      $this->view->render(
        'list',
        [
          'notes'=> $this->database->getNotes(),
          'before'=>$this->request->getParam('before') ?? null,
          'error'=>$this->request->getParam('error')?? null,
        ]
    ); 
    }

    public function editAction(){
      $noteId = (int)($this->request->getParam('id'));
      if(!$noteId){
        $this->redirect('/',['error'=>"missingNoteID"]);
      }
      $this->view->render('edit');
      
    }

    private function redirect(string $to,array $params){
      $params = implode('&',$params);
      $location = $to;
      header("Location: $to?error=missingNoteId");
      exit;
    }



       
    
}


?>