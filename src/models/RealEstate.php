<?php
namespace Src\Models;

class RealEstate
{
    public $id;
    public $title;
    public $description;
    public $location;

    public function __construct($id, $title, $description, $location)
    {
        $this->id          = $id;
        $this->title       = $title;
        $this->description = $description;
        $this->location    = $location;
    }

    // Example static data (later replace with DB)
    public static function all()
    {
        return [
            new RealEstate(1, "Century Heights", "Luxury apartments in Dhaka", "Dhaka"),
            new RealEstate(2, "Green Valley", "Modern residential township", "Chittagong"),
        ];
    }

    public static function find($id)
    {
        foreach (self::all() as $project) {
            if ($project->id == $id) return $project;
        }
        return null;
    }
}
