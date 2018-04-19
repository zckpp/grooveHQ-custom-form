<form action="https://carnegiescience.edu/sites/default/groove/groove.php" method="post">
    <div class="form-group">
        <label for="name">Name <span style="color:red">*</span></label>
        <input type="text" class="form-control" id="name" name="name" aria-describedby="emailHelp" placeholder="Enter your name" required>
    </div>
    <div class="form-group">
        <label for="to">Email address <span style="color:red">*</span></label>
        <input type="email" class="form-control" id="to" name="to" aria-describedby="emailHelp" placeholder="Enter email" required>
    </div>
    <div class="form-group">
        <label for="subject">Subject</label>
        <select class="form-control" id="subject" name="subject">
            <option value="" selected="selected">--select an item--</option>
            <option>Budget Modification</option>
            <option>Sub Signature request</option>
            <option>Certification</option>
            <option>Grant application submital</option>
            <option>Award set-up</option>
            <option>Proposal review & fund #</option>
        </select>
    </div>
    <div class="form-group">
        <label for="tag">Add Labels</label>
        <select multiple class="form-control" id="tag" name="tag[]">
            <option>GEO-4</option>
            <option>DTM-3</option>
            <option>OBS-7</option>
            <option>EMB-5</option>
            <option>PBIO</option>
        </select>
        <small id="taglHelp" class="form-text text-muted">You can select multiple tags by Ctrl + click.</small>
    </div>
    <div class="form-group">
        <label for="priority">Is this request urgent?</label>
        <input type="radio" name="priority" value="yes"> Yes<br>
        <input type="radio" name="priority" value="no" checked> No<br>
    </div>
    <div class="form-group">
        <label for="body">Message</label>
        <textarea class="form-control" id="body" name="body" rows="3"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>