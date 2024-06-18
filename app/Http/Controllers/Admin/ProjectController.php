<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Str;   // per usare gli l'helpers (si occupa di manipolare le stringhe, in questo caso lo uso per lo slug)
use Illuminate\Validation\Rule; // per usare la classe rule in update nella validazione 
use Illuminate\Support\Facades\Storage;  // per usare la classe Storage nello store ( per l'upload dei file)
use App\Models\Type;
use App\Models\Technology;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $projects = Project::all();  // per prendere tutti i projects utilizzo il Model (che ho importato sopra )

        //dd($projects);
        
        $data = [
            'projects' => $projects
        ];


        return view('admin.projects.index', $data);   // index si trova nella cartella projects di admin. Con view devo usare la dot.notation
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $types = Type::all();
        $technologies = Technology::all();
        
        $data = [
            'types' => $types,
            'technologies' => $technologies
        ];

        
        return view('admin.projects.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        

         // validazione dei dati del form prima di proseguire con il resto del codice
        $validated = $request->validate([
            'name' => 'required|unique:projects,name|min:5|max:200',   // unique vuole il nome della tabella e il nome della colonna
            'client_name' => 'required|min:5|max:250',
            'summary' => 'nullable|min:10|max:500|',
            'cover_image' => 'nullable|image|max:512',
            'type_id' => 'nullable|exists:types,id', 
            'technologies' => 'nullable|exists:technologies,id'
        ],
        
        [
            'name.required' => 'Add a name',
            'name.min' => 'Minimum 5 character',
            'name.max' => 'Maximum 200 characters', 
            'name.unique' => 'This name already exists', 
            'client_name.required' => 'Add a client name', 
            'client_name.min' => 'Minimum 5 characters', 
            'client_name.max' => 'Maximum 250 characters', 
            'summary.min' => 'Minimum 10 characters', 
            'summary.max' => 'Maximum 500 characters', 

        ]
        
    );

        $formData = $request->all();
        // dd($formData);

        // solo se l'utente ha caricato la cover image
        if($request->hasFile('cover_image')) {
            // upload del file nella cartella pubblica -  project_images è la sottocartella di public che mi crea, $formData['cover_image'] è la chiave dove c'è il file, è un'istanza di UploadedFile
            $img_path = Storage::disk('public')->put('project_images', $formData['cover_image']);
            // salvare nella colonna cover_image del db il path all'immagine caricata
            $formData['cover_image'] = $img_path;
            
        }
        



        $newProject = new Project();
        //senza fillable
        
        // $newProject->name = $formData['name'];
        // $newProject->slug = Str::slug($newProject->name , '-');
        // $newProject->client_name = $formData['client_name'];
        // $newProject->summary = $formData['summary'];
        // $newProject->save();

        // con fillable
        $newProject->fill($formData);   // usando il fillable ricordarsi di autorizzare le colonne fillabili nel model
        $newProject->slug = Str::slug($newProject->name , '-');  // se uso $newProject->name prima del fill avrei valore vuoto, dovrei usare invece $formData
        $newProject->save();  // laravel crea una nuova riga del database
        
        // attaccare le technologies scelte dall'utente al project creato
        if($request->has('technologies')) {
            $newProject->technologies()->attach($formData['technologies']);
        }


        // messaggio flash creazione progetto
        session()->flash('success', 'Project created!'); 
         // una volta creata una nuova riga del database vengo reindrizzato alla pagina del singolo prodotto creato. Uso ilredirect allo show e non il view allo show per tenere i compiti divisi.
        return redirect()->route('admin.projects.show', ['project' => $newProject->id]);  

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project) // versione con dependecy injection
    {
       

       $data = [
        'project' => $project
        
       ];
       
       // dd($data);
       

       return view('admin.projects.show', $data);
       // return view('admin.projects.show', compact('project'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {   
        //dd($project);

        $types = Type::all();
        $technologies = Technology::all();
        

        $data = [
            'project'=> $project,
            'types' => $types,
            'technologies' => $technologies

        ];
        
    
        return view('admin.projects.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {   

        // validazione dei dati del form prima di proseguire con il resto del codice
        $validated = $request->validate([
            //'name' => 'required|unique:projects,name|min:5|max:200',   // unique vuole il nome della tabella e il nome della colonna
            // uso questa sintassi per permettere di ignorare la regola del nome unique quando un project e' modificato
            //Forcing A Unique Rule To Ignore A Given ID:    https://laravel.com/docs/9.x/validation#introduction
            'name' => [
                'required',
                'min:5',
                'max:200',
                //'unique:projects,name'
                Rule::unique('projects')->ignore($project->id),   // non applicare la regola se il nome uguale allo stesso post che sto' modificando 
            ],
            'client_name' => 'required|min:5|max:250',
            'summary' => 'nullable|min:10|max:500|',
            'cover_image' => 'nullable|image|max:512',
            'type_id' => 'nullable|exists:types,id',
            'technologies' => 'nullable|exists:technologies,id' // qui nullable non e' necessario
        ],
        // custom error message
        [
            'name.required' => 'Add a name',
            'name.min' => 'Minimum 5 character',
            'name.max' => 'Maximum 200 characters', 
            'name.unique' => 'This name already exists', 
            'client_name.required' => 'Add a client name', 
            'client_name.min' => 'Minimum 5 characters', 
            'client_name.max' => 'Maximum 250 characters', 
            'summary.min' => 'Minimum 10 characters', 
            'summary.max' => 'Maximum 500 characters', 

        ]
        
    );

        $formData = $request->all();
        // dd($formData);

        // solo se l'utente ha caricato una nuova la cover image
        if($request->hasFile('cover_image')) {
            //se c'era gia una cover image la cancello dallo storage
            if($project->cover_image) { 
                Storage::delete($project->cover_image); 
            }

            // upload del file nella cartella pubblica -  project_images è la sottocartella di public che mi crea, $formData['cover_image'] è la chiave dove c'è il file, è un'istanza di UploadedFile
            $img_path = Storage::disk('public')->put('project_images', $formData['cover_image']);
            // salvare nella colonna cover_image del db il path all'immagine caricata
            $formData['cover_image'] = $img_path;
            
        }
        
        $project->slug = Str::slug($formData['name'] , '-'); // se usassi $project invece di formData farei lo slug sul valore vecchio
        $project->update($formData);

        if($request->has('technologies')) {
            $project->technologies()->sync($formData['technologies']);
        } else {
            $project->technologies()->detach(); // se l'utente deseleziona tutte le checkbox faccio detach altrimenti mi rimarrebbero le selezioni vecchie
        }

        // messaggio flash creazione progetto
        session()->flash('success', 'Project modified!'); 

        return redirect()->route('admin.projects.show', ['project' => $project->id]); // mostrare la pagina show e' compito di show, non di update, per questo faccio il redirect a show (che fara' view) e non faccio view direttamente

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {

        $project->delete();
        
        
        return redirect()->route('admin.projects.index');
       

    }
}
