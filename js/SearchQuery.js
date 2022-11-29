jQuery(document).ready(function ($) {
	$("#search_lexica_btn").on("click", function (event) {
		$(".loader").show();
		var search = $("#search_lexica_term").val();
		var displayResultsClass = $("#query-results");
		$.ajax({
			type: "POST",
			url: searchQuery.root_url + "/wp-json/search-lexica-form/v1/send-query",
			data: {
				search_query: search,
			},
			success: function (images) {
				displayResultsClass.html("");
				displayResultsClass.append(
					`<div class="gallery-image">${images.data
						.map(
							(img_data) =>
								`<div class="img-box">
								<img src="${img_data.src}" alt="${img_data.prompt}"/><div class="transparent-box">
								<div class="caption">
								  <p>${img_data.prompt}</p>
								  <p class="opacity-low">${search}</p>
								</div>
							  </div> 
							</div>`
						)
						.join("")}</div>`
				);
			},
			beforeSend: function (xhr) {
				xhr.setRequestHeader("X-WP-Nonce", searchQuery.nonce);
				if (!search) {
					$(".loader").hide();
					return false;
				}
				// clear previous search results
				displayResultsClass.html("");
			},
		}).done(function () {
			$(".loader").hide();
		});
		event.preventDefault();
		displayResultsClass.removeClass("hidden");
	});
});
