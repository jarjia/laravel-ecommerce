<form style="display: flex; justify-content: center; flex-direction:column; gap: 4px" id="groupForm" method="GET" action="{{ route('table', ['group' => 'jarja']) }}">
    <label for="groupInput">ჯგუფის კოდი</label>
    <input style="padding: 6px 3px;" type="text" id="groupInput" name="group" autocomplete="on"/>
    <button type="submit">ცხრილის ნახვა</button>
</form>

<script>
    document.getElementById('groupForm').addEventListener('submit', function(event) {
        event.preventDefault(); 
        var groupValue = document.getElementById('groupInput').value;

        this.action = "{{ url('table') }}/" + groupValue;

        this.submit();
    });
</script>
