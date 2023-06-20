import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AlbumManagementComponent } from './album-management.component';

describe('AlbumManagementComponent', () => {
  let component: AlbumManagementComponent;
  let fixture: ComponentFixture<AlbumManagementComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ AlbumManagementComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(AlbumManagementComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
