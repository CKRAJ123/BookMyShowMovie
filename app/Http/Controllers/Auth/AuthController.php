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
use App\Models\My_booking;
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
    protected $paymentServiceInterface;

    public function __construct(PaymentServiceInterface $paymentServiceInterface) {
      
        $this->paymentServiceInterface=$paymentServiceInterface;
    //    $this->reject= $attendance;
   }
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


    public function book_movie($id){
        // dd($user_id);
        $theaters=Theater::all();
        //dd( $theaters);
       // $=User::find($user_id)->first;
       // dd($user);
       $film = Film::where('id',$id)->first();
        // dd($film->movie_name);
        return view('theater')->with('theaters',$theaters)->with('film',$film);
    }
    public function seat_booking($theater_id){
        //dd($theater_id);
        $theater = Theater::where('id',$theater_id)->first();
        return view('SeatBookShow')->with('theater',$theater);
    }

    public function paynow(Request $request,$theater_id){
        //dd( $theater_id);
        $theater = Theater::where('id',$theater_id)->first();
        $data = $request->input();
        //dd($data );
        $seatsBooked = $data['seat_booked'];
       // dd( $seatsBooked);
        $data['amount'] = count($seatsBooked) * 200;
        //dd(count($seatsBooked));
        $data['movie']=$theater->movie_name;
        $getPaymentToken = $this->generateBookingPaymentToken($data['amount']);
       // dd( $getPaymentToken);
        $seatbooked = new Seatbooked;
        $seatbooked->total_seat_selected=count($seatsBooked);
        $seatbooked->total_amount=$data['amount'];
        $seatbooked->name=$data['name'];
        $seatbooked->token_id=$getPaymentToken;
        $seatbooked->status="pending";
        $seatbooked->movie_name=$theater->movie_name;
        $seatbooked->save();
       // dd($seatbooked->id);
        return view('SeatBookingPayment')->with('token', $getPaymentToken)->with('data', $data)->with('id', $seatbooked->id)->with('theater_id', $theater_id);
    }
    /**
     * @throws GuzzleException
     */
    public function generateBookingPaymentToken($amount)
    {
    
          return $this->paymentServiceInterface->doMoviePayment($amount);

    }
    public function MovieTicketSuccess($theater_id,$seatbooked_id){
         // dd($theater_id);
        $seatbooked = Seatbooked::where('id',$seatbooked_id)->first();
        $seatbooked->status="success";
        $seatbooked->save();

        $my_booking=new My_booking;
        $my_booking->theater_id=$theater_id;
        $my_booking->seatbooked_id=$seatbooked_id;
        $my_booking->save();
        //return "Payment has been successfully completed";
        $films = Film::paginate(3);
        $user=User::first();
     return view('welcome')->with('films',$films)->with('user',$user)->withSuccess('yes Your seat has been booked and payment completed');
       // return view('welcome')->withSuccess('yes Your seat has been booked and payment completed') ;
    }
   
}