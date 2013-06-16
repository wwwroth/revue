revue = {
	init: function()
	{
		$('.venueActionRate').live('click', function()
		{
			$(this).parent().siblings('.actionRate').slideToggle();
			$(this).parent().siblings('.actionReview').slideUp();
		});
		
		$('.venueActionReview').live('click', function()
		{
			$(this).parent().siblings('.actionReview').slideToggle();
			$(this).parent().siblings('.actionRate').slideUp();
		});
		
		$('.rateButton').live('click', function()
		{
			$(this).addClass('selected');
			$(this).siblings().removeClass('selected');
		});
		
		$('.venueReviewsHeader').live('click', function()
		{
			$(this).siblings('.reviews').slideToggle();
			revue.getReviews($(this).siblings('.reviews').first(), $(this).attr("vid"));
		});
		
		$('.rateSubmit').live('click', function()
		{
			revue.submitRating($(this).attr("vid"), $(this).siblings('.selected').first().text())
		});
		
		$('.reviewSubmit').live('click', function()
		{
			revue.submitReview($(this).attr("vid"), $(this).siblings('.addReviewName').first().val(),
					   $(this).siblings('.addReviewText').first().val());
		});
		
		$('.reviewEdit').live('click', function()
		{
			revue.editReview($(this));	
		});
		
		$('.reviewSave').live('click', function()
		{
			revue.saveReview($(this), $(this).attr("rid"));	
		});
		
		$('.reviewDelete').live('click', function()
		{
			revue.deleteReview($(this).attr("rid"));
		});
		
		$('#addSubmit').click(function()
		{
			revue.submitVenue();
		})
		
		$.ajax(
		{
			url: "/services/venue/list/",
			success: function(data)
			{
				revue.drawVenues(data);
			}
		});
	},
	
	drawVenues: function(venues)
	{
		venues = $.parseJSON(venues);
		if (venues.status == "OK")
		{
			venueHTML = $('.venue').first().clone();
			$('.venue').remove();
			
			$.each(venues.venues, function(i)
			{
				resultHTML = venueHTML.clone();
				$('.venueName', resultHTML).text(this.venueName);
				$('.venueLocation', resultHTML).text(this.location);
				$('.venueEmail', resultHTML).text(this.emailAddress);
				
				if (this.numberOfRatings > 0)
					$('.venueRating', resultHTML).text("Rating: " + this.averageRating + " (based on " + this.numberOfRatings + " ratings)");
				else
					$('.venueRating', resultHTML).text("No ratings");
					
				if (this.numberOfReviews > 0)
				{
					$('.venueReviewsHeader', resultHTML).text(this.numberOfReviews + " reviews. Click to show/hide.");
					$('.venueReviewsHeader', resultHTML).attr('vid', this.id);
				}
				else
					$('.venueReviews', resultHTML).remove();
					
				$('.rateSubmit', resultHTML).attr('vid', this.id);
				$('.reviewSubmit', resultHTML).attr('vid', this.id);
					
				$('#venues').append(resultHTML);
			});
		}
		else
		{
			alert(venues.errmsg);
		}
	},
	
	getReviews: function(obj, vid)
	{
		$.ajax(
		{
			url: "/services/review/list/",
			data: {
				venueId: vid
			},
			success: function(data)
			{
				revue.drawReviews(obj, data);
			}
		});
	},
	
	drawReviews: function(obj, reviews)
	{
		if (reviews.status == "OK")
		{
			reviewHTML = $('.review').first().clone();
			obj.find('.review').remove();
			
			$.each(reviews.reviews, function(i)
			{
				resultHTML = reviewHTML.clone();
				
				$('.reviewHeader', resultHTML).text("Submitted by " + this.author + " on " + this.datetime);
				$('.reviewText', resultHTML).text(this.reviewText);
				$('.reviewTextInput', resultHTML).val(this.reviewText);
				$('.reviewSave', resultHTML).attr('rid', this.id);
				$('.reviewDelete', resultHTML).attr('rid', this.id);
				
				obj.append(resultHTML);
			});
		}
		else
		{
			alert(reviews.errmsg);
		}
	},
	
	submitRating: function(vid, rating)
	{
		$.ajax(
		{
			url: "/services/rating/add/",
			data: {
				venueId: vid,
				rating: rating
			},
			success: function(data)
			{
				data = $.parseJSON(data);
				if (data.status == "OK")
					location.href='/';
				else
					alert(data.errmsg);
			}
		});
	},
	
	submitReview: function(vid, name, review)
	{
		$.ajax(
		{
			url: "/services/review/add/",
			data: {
				venueId: vid,
				author: name,
				reviewText: review
			},
			success: function(data)
			{
				data = $.parseJSON(data);
				if (data.status == "OK")
					location.href='/';
				else
					alert(data.errmsg);
			}
		});
	},
	
	editReview: function(obj)
	{
		obj.siblings('.reviewText').hide();
		obj.siblings('.reviewTextEdit').show();
		obj.siblings('.reviewSave').show();
		obj.hide();
	},
	
	saveReview: function(obj, rid)
	{
		$.ajax(
		{
			url: "/services/review/update/",
			data: {
				reviewId: rid,
				reviewText: obj.siblings('.reviewTextEdit').find('.reviewTextInput').val()
			},
			success: function(data)
			{
				data = $.parseJSON(data);
				if (data.status == "OK")
					location.href='/';
				else
					alert(data.errmsg);
			}
		});
	},
	
	deleteReview: function(rid)
	{
		$.ajax(
		{
			url: "/services/review/delete/",
			data: {
				reviewId: rid
			},
			success: function(data)
			{
				data = $.parseJSON(data);
				if (data.status == "OK")
					location.href='/';
				else
					alert(data.errmsg);
			}
		});
	},
	
	submitVenue: function()
	{
		$.ajax(
		{
			url: "/services/venue/add/",
			data: {
				venueName: $('#addNameInput').val(),
				emailAddress: $('#addEmailInput').val(),
				zipcode: $('#addZipInput').val()
			},
			success: function(data)
			{
				data = $.parseJSON(data);
				if (data.status == "OK")
					location.href='/';
				else
					alert(data.errmsg);
			}
		});
	}
}

$(document).ready(function()
{
	revue.init();
});