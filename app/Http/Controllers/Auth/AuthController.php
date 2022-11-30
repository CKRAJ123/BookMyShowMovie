<?php
  
namespace App\Http\Controllers\Auth;
  
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\User;
use Hash;
use App\Models\Film;
use App\Models\Theater;
use App\Services\PaymentServiceInterface;
use App\Interface\SeatInterface;
use App\Models\Seatbooked;
class AuthController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
   
    // protected $seatinterface;

    // public function __construct(SeatInterface $seatinterface)
    // {
    //     $this->seatinterface = $seatinterface;

    // }
    public function index()
    {
        return view('auth.login');
    }  
      
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function registration()
    {
        return view('auth.registration');
    }
      
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function postLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
   
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended('dashboard')
                        ->withSuccess('You have Successfully loggedin');
        }
  
        return redirect("login")->withSuccess('Oppes! You have entered invalid credentials');
    }
      
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function postRegistration(Request $request)
    {  
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
           
        $data = $request->all();
        $check = $this->create($data);
         
        return redirect("dashboard")->withSuccess('Great! You have Successfully loggedin');
    }
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function dashboard()
    {
        if(Auth::check()){

            $films = Film::paginate(3);
           // User::paginate(15);
           $user=User::first();
           //dd($user->id);
            return view('welcome')->with('films',$films)->with('user',$user);
        }
  
        return redirect("login")->withSuccess('Opps! You do not have access');
    }
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function create(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password'])
      ]);
    }
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function logout() {
        Session::flush();
        Auth::logout();
  
        return Redirect('login');
    }


    public function book_movie(){
        // dd($user_id);
        $theaters=Theater::all();
        //dd( $theaters);
       // $user=User::find($user_id)->first;
       // dd($user);
        return view('theater')->with('theaters',$theaters);
    }
    public function seat_booking(){
        return view('SeatBookShow');
    }

    public function paynow(Request $request){
        //dd( $request);
        $data = $request->input();
       // dd($data );
        $seatsBooked = $data['seat_booked'];
       // dd( $seatsBooked);
        $data['amount'] = count($seatsBooked) * 200;
        //dd(count($seatsBooked));
        $getPaymentToken = $this->generateBookingPaymentToken($data['amount']);
       // dd( $getPaymentToken);
        $seatbooked = new Seatbooked;
        $seatbooked->total_seat_selected=count($seatsBooked);
        $seatbooked->total_amount=$data['amount'];
        $seatbooked->name=$data['name'];
        $seatbooked->token_id=$getPaymentToken;
        $seatbooked->status="pending";
        $seatbooked->movie_name=$data['movie'];
        $seatbooked->save();
       // dd($seatbooked->id);
        return view('SeatBookingPayment')->with('token', $getPaymentToken)->with('data', $data)->with('id', $seatbooked->id);
    }
    /**
     * @throws GuzzleException
     */
    public function generateBookingPaymentToken($amount)
    {
        $bytes = random_bytes(20);

        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'https://sandbox-icp-api.bankopen.co/api/payment_token', [
            'body' => json_encode([
                "amount" => $amount,
                "contact_number" => "8043234223",
                "email_id" => "code@gmail.com",
                "currency" => "INR",
                "mtx" => bin2hex($bytes)
            ]),
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer c6b19ee0-284e-11ed-a4b4-91d56a37fb20:1c7903dfe788673d46d6a2fc898756ef229efb6b',
                'Content-Type' => 'application/json',
            ],
        ]);

        $responseData = json_decode($response->getBody()->getContents());
       //dd($responseData);
        return $responseData->id;

    }
    public function MovieTicketSuccess($token,$id){
         // dd($id);
        $seatbooked = Seatbooked::where('id',$id)->first();
        $seatbooked->status="success";
        $seatbooked->save();
        return Redirect('seat_booking')->withSuccess('yes Your seat has been booked and payment completed') ;
    }
   
}