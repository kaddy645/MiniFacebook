$(document).ready(function () {

  
    /*create New Post*/

    $(".addPost").click(function () {
        var post_text = $("textarea").val();

        if ($.trim($("textarea").val()) == '') {
            return alert("Please add some Text to create New Post!");
        }

        $("textarea").val('') // Clear the textbox
        if ($("div").hasClass("f-card")) {
            // Finding total number of elements added
            var total_element = $(".f-card").length;
            // last <div> with element class id
            var lastid = $(".f-card:first").attr("id");
            var split_id = lastid.split("_");
            var nextindex = Number(split_id[1]) + 1;

        } else {
            var nextindex = 1;
        }

        $.post("ajax_actions.php", {
            action_area: "user",
            dataType: 'html',
            action: "new_post",
            post: post_text,
            nextindex: nextindex
        }, function (data) {
            console.log(data);
            ($(".listPost").prepend(data.data))
        }, "json");
    })

    /*Make Post Editable*/

    $(".listPost").on("click", ".editable", function () {
        var edit_id = this.id; //this is id belongs to input button edit e.g(editable_1)
        console.log(edit_id)
        var split_id_edit = edit_id.split("_");
        var editedindex = split_id_edit[1];
        console.log(editedindex)
        $("#" + edit_id).val('Update'); //change button value to update
        $("#" + edit_id).addClass("update"); //add class update so we can find next time
        $("#edit_" + editedindex).attr('contenteditable', 'true');
        $("#edit_" + editedindex).focus();
        console.log($("#edit_" + editedindex))
    });

    /*Update with new edited Post*/

    $(".listPost").on("click", ".update", function () { //when click on update get updated text
        var updated_id = this.id; //this is id belongs to input button edit e.g(editable_1)
        var split_id_update = updated_id.split("_");
        var updatedindex = split_id_update[1];
        $("#" + updated_id).val('Edit'); // change back to edit
        $("#edit_" + updatedindex).attr('contenteditable', 'false');
        $("#edit_" + updatedindex).blur();
        $("#" + updated_id).removeClass("update"); //Remove class update once textrea is updated

        var edited_post = $("#edit_" + updatedindex).text();
        console.log(edited_post);

        $.post("ajax_actions.php", {
            action_area: "user",
            dataType: 'html',
            action: "edit_post",
            post_id: updatedindex,
            post: edited_post,
        }, function (data) {
            console.log(data);
        }, "json");


    });


    // Remove element
    $('.listPost').on('click', '.remove', function () {

        var id = this.id;
        var split_id = id.split("_");
        var deleteindex = split_id[1];


        $.post("ajax_actions.php", {
                action_area: "user",
                action: "delete_post",
                post_id : deleteindex
            }, function (data) {
                // Remove <div> with id
                 $("#div_" + deleteindex).remove();
            }, "json");


        

    });


    // Add new Comment
    $('.listPost').on('keypress', '.txtcomment', function (e) {


        if (!($(this).hasClass("comment"))) { //Handle First time Comment!

            var total_element = 0;
            // console.log("new" +     total_element);
            var lastid = 'comment_0';
            var split_id = 0
            var nextCommentindex = 1;
        } else {
            var total_element = $(".comment").length;
            //console.log("old" +     total_element);
            var lastid = $(".comment:first").attr("id");
            var split_id = lastid.split("_");
            var nextCommentindex = Number(split_id[1]) + 1;
        }

        if (e.keyCode == 13) { //Hnadle Enter after new comment added!

            var id = this.id;
            var comment_listID = id.split("_")[1];


            var comment_text = $("#" + id).val();

            if ($.trim($(id).val()) == '') {
                //return alert("Please add some Text to add comment!");
            }

            $("#" + id).val(''); // Clear the textbox for add new


            console.log(comment_listID);

            $.post("ajax_actions.php", {
                action_area: "user",
                action: "new_comment",
                comment_text: comment_text,
                post_id : comment_listID
            }, function (data) {
                ($("#comment_list_" + comment_listID).prepend(data.data))
            }, "json");


        }


    })


});