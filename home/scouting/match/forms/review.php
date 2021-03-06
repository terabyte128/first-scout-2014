<form id="review" class="scouting-form">
    <!-- 
    <label for="teamNumber">Team Number:
        <a href="#" id="teamNumber" class="editable"></a>
    </label>
    <br/> 
    <label for="teamNumber">Match Number:
        <a href="#" id="matchNumber" class="editable"></a>
    </label>
    <label for="allianceColor">
        
    </label>
    -->
    <label>Submit data to the database and:</label>
    <div class="form-group">
        <button class="btn btn-lg btn-info submit-data" type="button" style="width: 250px;" onclick="updateDatabase(true);">Go to scouting home</button>
    </div>
    <div class="form-group">
        <button class="btn btn-lg btn-success submit-data" type="button" style="width: 250px;" onclick="updateDatabase(false);">Scout another match</button>
    </div>
    <br />
    <br />
    <div class="form-group">
        <button class="btn btn-lg btn-danger" type="button" style="width: 250px;" id="discardData">Discard this data</button>
    </div>

</form>

<script type="text/javascript">

    $('#pageNameTitle').text("Finish Scouting")
    //document.location.hash = "review";


    $(function() {
        if (parseInt(localStorage.teamNumber) === loggedInTeam && parseInt(localStorage.matchOutcome) === 0) {
            showMessage("<h3><marquee scrollamount='10' scrolldelay='1'>Congratulations! Your team won the match!</marquee></h3>", "success");
        }
    });


    $("#discardData").click(function() {
        if (confirm("This will discard all the data for this match! Are you sure you wish to continue?")) {
            localStorage.discardedMatch = JSON.stringify(localStorage);
            localStorage.clear();
            loadPageWithMessage("/home", "Match data discarded.", "danger");
        }
    });

    function pushToLocalStorage() {
        changePhase("prematch");
    }

    function pullFromLocalStorage() {
        //dummy function
    }

    function updateDatabase(goHome) {
        $(".submit-data").button("loading");
        $.ajax({
            url: 'push-to-database.php',
            type: "POST",
            data: {
                'matchData': JSON.stringify(localStorage)
            },
            success: function(response) {
                if(response === "200 Success") {
                    localStorage.clear();
                    if(goHome) {
                        loadPageWithMessage("/", "Match data submitted.", "success");
                    } else {
                        $("#teamNumberTitle").text("");
                        window.location = "#prematch";
                        loadPageWithMessage("#prematch", "Match data submitted.", "success");
                    }
                } else {
                    //showMessage(response, "danger");
                    showMessage("You must start scouting from the beginning of the match. <a href='#prematch' onclick='hideMessage();'>Go there.</a>", "danger");
                }
            }
        })
    }
</script>