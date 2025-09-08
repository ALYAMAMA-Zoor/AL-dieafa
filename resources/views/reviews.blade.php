@extends('layouts.footer')

@section('content')
<div class="testimonail-section mt-5 mb-5 text-center">
<div class="container">
	<div class="row">
		<div class="col-lg-8 mb-20 mt-20 text-center  mb-lg-0">
  
					<div class="section-title ">	
						<h3 ><span class="orange-text">أراء</span> العملاء</h3>
					
				
					<div class="contact-form">
						<form method="post" action="/storeReviews" >
							@csrf
                                <p style="display:flex">

                                    <input type="text"  placeholder="name" class="ml-4"required  name="name" id="name" value={{old('name')}}>
										<span class="text-danger">
										@error('name')
										{{$message}}
										@enderror
										</span>
								
                                    <input type="text" placeholder="phone" name="phone" id="phone" value= {{ old('phone')}}>
									    <span class="text-danger">
										@error('phone')
										{{$message}}
										@enderror
										</span>
                                </p>

                                <p style="display:flex">

								<input type="email" placeholder="email" class="ml-4" name="email" id="email" value= {{ old('email')}}>
									<span class="text-danger">
											
										@error('email')
										{{$message}}
										@enderror
										</span>
								<input type="text" placeholder="subject" name="subject" id="subject"value={{ old('subject')}}>
									<span class="text-danger">
											
										@error('subject')
										{{$message}}
										@enderror
										</span>
							</p>
							
							<p><textarea name="message" id="message" cols="30" rows="10" placeholder="message">{{ old('message')}}</textarea></p>
							<span class="text-danger">
											
										@error('message')
										{{$message}}
										@enderror
							</span>
							
						
							<p><input type="submit" value="Submit" /></p>
						</form>
					</div>
					</div>
		</div>
		</div>
	

			<div class="row">
				<div class="col-lg-10 offset-lg-1 text-center">
					<div class="testimonial-sliders">
                        @foreach($reviews as $item)

						<div class="single-testimonial-slider">
							<div class="client-avater">
								<img src="" alt="">
							</div>
							<div class="client-meta">
								<h3>{{$item -> name}} <span>{{$item-> subject}}</span></h3>
								<p class="testimonial-body">
                                    {{$item-> message}}
								</p>
								<div class="last-icon">
									<i class="fas fa-quote-right"></i>
								</div>
							</div>
						</div>
                        @endforeach
					</div>
				</div>
			</div>
	

	</div>
	</div>

@endsection