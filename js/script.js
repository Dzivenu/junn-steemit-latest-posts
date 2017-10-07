function compare(a,b) 
{
	if (a.created > b.created)
		return -1;
	if (a.created < b.created)
		return 1;
	return 0;
}
jQuery(document).ready(function($) 
{
	steem.api.getState(args.query_tag, function(err, result){
    //console.log(err, result);
    
    var html_text = "";
    var author_set = new Set();
    var author_img = new Object();
    var content_obj = result.content;
    var content_arr = new Array();
    //console.log("contents", content_obj);
 	for(key in content_obj)
 	{
 		if (content_obj.hasOwnProperty(key)) 
 		{
	 		author_set.add(content_obj[key].author);
	 		content_arr.push(content_obj[key]);
 		}
 	}
 	//console.log("contents_arr", content_arr);
 	content_arr.sort(compare);

 	steem.api.getAccounts(Array.from(author_set), function(err, result2) {
			//console.log("Accounts",err, result2);

			for(var idx=0;idx<result2.length;idx+=1)
			{
				var author = result2[idx];
				//console.log(idx,author);
				if(author.json_metadata!="")
				{
					author_img[author.name] = JSON.parse(author.json_metadata);
				}
				else
				{
					console.log("test","no profile");
					author_img[author.name] = new Object();
					author_img[author.name]["profile"] = new Object();
					author_img[author.name].profile["profile_image"] = args.no_profile_img;
				}
			}
			
			//console.log("author_img",author_img);
		
		var nrow = args.nrow;
		if(nrow > content_arr.length)
		{
			nrow = content_arr.length;
		}
	 	for(var idx=0;idx<nrow;idx++)
	 	{
	 		var this_content = content_arr[idx];
	 		//console.log("Obj",this_content);

	 		html_text = html_text + "<div class=\"steem_container\" style=\"height:50px;width:100%;\">"+ "<a href=\"https://steemit.com/@"+this_content.author+"\" target=\"_blank\"><img style=\"display: block;position:absolute;height:50px;width:50px;border-radius: 50%;\" src=\"" + author_img[this_content.author].profile.profile_image + "\"/></a>" + "<div class=\"steem_container_text\" style=\"position: relative;margin-left:50px;padding-left:10px;padding-right:10px;white-space: nowrap;overflow:hidden; text-overflow: ellipsis;top: 50%;transform: translateY(-50%);\"><a target=\"_blank\" href=\"https://steemit.com"+this_content.url+"\">" + this_content.title + "</a></div></div>";
	 	//
	 	}
	 	
		//console.log(html_text);
		jQuery("#jslp_latest_steem_view").html(html_text);
		});
	});
});