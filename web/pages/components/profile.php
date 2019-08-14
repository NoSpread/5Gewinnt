<div class="menu-overlay">
    <div class="container">
        <div class="menu">
            <div class="menu-close">
                <button class="mdi mdi-24px mdi-close"></button>
            </div>
            <div class="profile-header">
                <div class="t-48px"><?php 
                                        echo $_SESSION['username']; 
                                    ?></div>
                <div>RANK #$rank</div>
            </div>
            <div class="profile-content">
                <div class="row">
                    <div class="col">
                        <i class="t-48px mdi mdi-trophy"></i>
                        <div class="t-48px">$wins</div>
                        <div>WINS</div>
                    </div>
                    <div class="col">
                        <i class="t-48px mdi mdi-flash"></i>
                        <div class="t-48px">$losses</div>
                        <div>LOSSES</div>
                    </div>
                    <div class="col">
                        <i class="t-48px mdi mdi-script"></i>
                        <div class="t-48px">$ties</div>
                        <div>TIES</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

