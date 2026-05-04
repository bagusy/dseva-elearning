<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'name',
        'industry',
        'size',
    ];

    const COMPANY_SIZE_LIST = [
        'Micro' => '1 - 10 People',
        'Small' => '10 - 50 People',
        'Medium Low' => '50 - 100 People',
        'Medium High' => '100 - 250 People',
        'Large Low' => '250 - 500 People',
        'Large High' => '>500 People',
    ];

    const COMPANY_INDUSTRY_LIST = [
        'Software & Comp. Serv.',
        'Chemicals',
        'Forestry & Paper',
        'Ind. Metals & Mining',
        'Mining',
        'Constr. & Materials',
        'General Industrials',
        'Elec. & Elec. Equip.',
        'Indust. Engineering',
        'Indust. Transp.',
        'Support Services',
        'Automobiles & Parts',
        'Beverages',
        'Food Producers',
        'Health Care Equip. & Services',
        'Pharm & Biotech.',
        'Food & Drug Retailers',
        'General Retailers',
        'Media',
        'Travel & Leisure',
        'Fixed Line Telecoms.',
        'Banks',
        'Non-life Insurance',
        'Life Insurance',
        'General Financial',
        'Equity Investment Instruments',
    ];

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user()
    {
        return $this->owner();
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
